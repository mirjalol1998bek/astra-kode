<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/companies')]
class CompanyController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request, CompanyRepository $repository): JsonResponse
    {
        $companies = $repository->search($request->query->all());

        return $this->json(array_map(fn (Company $c) => $c->toArray(), $companies));
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->save(new Company(), $this->getBody($request), true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Company $company, Request $request): JsonResponse
    {
        return $this->save($company, $this->getBody($request), false);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Company $company): JsonResponse
    {
        $this->em->remove($company);
        $this->em->flush();

        return $this->json(['message' => "Kompaniya o'chirildi"]);
    }

    private function save(Company $company, array $data, bool $isNew): JsonResponse
    {
        $company->setName(trim($data['name'] ?? ''));
        $company->setEmail(mb_strtolower(trim($data['email'] ?? '')));

        $errors = $this->violationsToArray($this->validator->validate($company));

        $password = $data['password'] ?? '';
        $passwordError = $this->checkPassword($password, $isNew);
        if ($passwordError) {
            $errors['password'] = $passwordError;
        }

        if ($errors) {
            if (!$isNew) {
                $this->em->refresh($company);
            }

            return $this->errorResponse($errors);
        }

        if ($password !== '') {
            $company->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }
        $company->setLastActivity(new \DateTimeImmutable());

        $this->em->persist($company);
        $this->em->flush();

        return $this->json($company->toArray(), $isNew ? 201 : 200);
    }
}
