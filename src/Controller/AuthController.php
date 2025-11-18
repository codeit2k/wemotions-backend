<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\ApiResponseService;

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
    public function register(Request $request, ApiResponseService $api): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate fields
        if (empty($data['email']) || empty($data['password'])) {

            [$response, $status] = $api->error(
                message: 'Email and password are required',
                statusCode: 400
            );

            return new JsonResponse($response, $status);
        }

        // Create user
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->hasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        // Success response
        [$response, $status] = $api->success(
            data: [
                'id' => $user->getId(),
                'email' => $user->getEmail()
            ],
            message: 'User registered successfully'
        );

        return new JsonResponse($response, $status);
    }

    // NOTE: login normally handled by LexikJWT bundle
}
