<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
    }

    #[Route('/api/auth/register', methods:['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['success'=>false,'message'=>'email and password required'], 400);
        }
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse(['success'=>true,'data'=>['id'=>$user->getId()],'message'=>'registered']);
    }

    // NOTE: Login and token issuing typically handled by Lexik bundle endpoints.
}
