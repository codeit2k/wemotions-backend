<?php
namespace App\MessageHandler;

use App\Message\ProcessVideoMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Process\Process;
use App\Repository\VideoRepository;

#[AsMessageHandler]
class ProcessVideoHandler
{
    private EntityManagerInterface $em;
    private VideoRepository $videoRepo;

    public function __construct(EntityManagerInterface $em, VideoRepository $videoRepo)
    {
        $this->em = $em;
        $this->videoRepo = $videoRepo;
    }

    public function __invoke(ProcessVideoMessage $msg)
    {
        $video = $this->videoRepo->find($msg->getVideoId());
        if (!$video) return;

        $path = $msg->getPath();

        try {
            $p = new Process(['ffprobe','-v','error','-show_entries','format=duration','-of','default=noprint_wrappers=1:nokey=1',$path]);
            $p->run();
            if ($p->isSuccessful()) {
                $duration = (int)round((float)trim($p->getOutput()));
                $video->setDurationSeconds($duration);
            }

            $thumb = dirname($path) . '/thumb.jpg';
            $p2 = new Process(['ffmpeg','-y','-i',$path,'-ss','00:00:01.000','-vframes','1',$thumb]);
            $p2->run();
            if (file_exists($thumb)) $video->setThumbnailPath($thumb);

            $video->setStatus('ready');
            $this->em->persist($video);
            $this->em->flush();
        } catch (\Exception $e) {
            $video->setStatus('failed');
            $this->em->persist($video);
            $this->em->flush();
        }
    }
}
