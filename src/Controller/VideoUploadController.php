<?php
namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\ProcessVideoMessage;

class VideoUploadController
{
    private string $uploadsDir;
    private EntityManagerInterface $em;
    private MessageBusInterface $bus;

    public function __construct(string $uploadsDir, EntityManagerInterface $em, MessageBusInterface $bus)
    {
        $this->uploadsDir = $uploadsDir;
        $this->em = $em;
        $this->bus = $bus;
    }

    #[Route('/api/videos/initiate', methods:['POST'])]
    public function initiate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $filename = $data['filename'] ?? 'video.mp4';
        $size = (int)($data['size'] ?? 0);
        $chunkSize = (int)($data['chunk_size'] ?? 5*1024*1024);
        $uploadId = bin2hex(random_bytes(12));
        $chunks = (int)ceil($size / $chunkSize);
        $sessionDir = rtrim($this->uploadsDir, '/') . '/' . $uploadId;
        @mkdir($sessionDir, 0755, true);
        file_put_contents($sessionDir . '/meta.json', json_encode(['filename'=>$filename,'size'=>$size,'chunk_size'=>$chunkSize,'chunks'=>$chunks]));
        return new JsonResponse(['success'=>true,'data'=>['upload_id'=>$uploadId,'chunks'=>$chunks]]);
    }

    #[Route('/api/videos/{uploadId}/chunk/{index}', methods:['PUT'])]
    public function chunkUpload(string $uploadId, int $index, Request $request): JsonResponse
    {
        $sessionDir = rtrim($this->uploadsDir, '/') . '/' . $uploadId;
        if (!is_dir($sessionDir)) return new JsonResponse(['success'=>false,'message'=>'invalid upload id'],400);
        $chunkPath = $sessionDir . '/chunk_'.$index;
        file_put_contents($chunkPath, $request->getContent());
        return new JsonResponse(['success'=>true,'index'=>$index]);
    }

    #[Route('/api/videos/{uploadId}/complete', methods:['POST'])]
    public function complete(string $uploadId, Request $request): JsonResponse
    {
        $sessionDir = rtrim($this->uploadsDir, '/') . '/' . $uploadId;
        $metaFile = $sessionDir . '/meta.json';
        if (!file_exists($metaFile)) return new JsonResponse(['success'=>false,'message'=>'no upload session'],400);
        $meta = json_decode(file_get_contents($metaFile), true);
        $chunks = $meta['chunks'];
        $finalPath = $sessionDir . '/' . basename($meta['filename']);
        $out = fopen($finalPath, 'wb');
        for ($i=0;$i<$chunks;$i++){
            $p = $sessionDir . '/chunk_'.$i;
            if (!file_exists($p)) {
                fclose($out);
                return new JsonResponse(['success'=>false,'message'=>"missing chunk $i"],400);
            }
            $in = fopen($p,'rb');
            stream_copy_to_stream($in, $out);
            fclose($in);
        }
        fclose($out);

        // create DB record
        $video = new Video();
        $video->setTitle($meta['filename']);
        $video->setStoragePath($finalPath);
        $video->setStatus('processing');
        $this->em->persist($video);
        $this->em->flush();

        $this->bus->dispatch(new ProcessVideoMessage($video->getId(), $finalPath));

        return new JsonResponse(['success'=>true,'data'=>['video_id'=>$video->getId()], 'message'=>'processing started']);
    }
}
