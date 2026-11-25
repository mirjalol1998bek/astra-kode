<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
class UserController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request, UserRepository $repository): JsonResponse
    {
        $users = $repository->search($request->query->all());

        return $this->json(array_map(fn (User $u) => $u->toArray(), $users));
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = new User();

        return $this->save($user, $this->getBody($request), true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(User $user, Request $request): JsonResponse
    {
        return $this->save($user, $this->getBody($request), false);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        if ($user === $this->getUser()) {
            return $this->json(['message' => "O'zingizni o'chira olmaysiz"], 400);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->json(['message' => "Foydalanuvchi o'chirildi"]);
    }

    private function save(User $user, array $data, bool $isNew): JsonResponse
    {
        $user->setName(trim($data['name'] ?? ''));
        $user->setEmail(mb_strtolower(trim($data['email'] ?? '')));

        $errors = $this->violationsToArray($this->validator->validate($user));

        $password = $data['password'] ?? '';
        $passwordError = $this->checkPassword($password, $isNew);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        if ($errors) {
            // o'zgartirishda xato bo'lsa entity'dagi o'zgarishlarni bekor qilamiz
            if (!$isNew) {
                $this->em->refresh($user);
            }

            return $this->errorResponse($errors);
        }

        if ($password !== '') {
            $user->setPassword($this->hasher->hashPassword($user, $password));
        }

        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user->toArray(), $isNew ? 201 : 200);
    }
}
