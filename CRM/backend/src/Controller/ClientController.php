<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/clients')]
class ClientController extends BaseController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private CompanyRepository $companies,
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(Request $request, ClientRepository $repository): JsonResponse
    {
        $clients = $repository->search($request->query->all());

        return $this->json(array_map(fn (Client $c) => $c->toArray(), $clients));
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->save(new Client(), $this->getBody($request), true);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Client $client, Request $request): JsonResponse
    {
        return $this->save($client, $this->getBody($request), false);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Client $client): JsonResponse
    {
        $this->em->remove($client);
        $this->em->flush();

        return $this->json(['message' => "Mijoz o'chirildi"]);
    }

    private function save(Client $client, array $data, bool $isNew): JsonResponse
    {
        $company = null;
        if (!empty($data['companyId'])) {
            $company = $this->companies->find((int) $data['companyId']);
        }

        $client->setName(trim($data['name'] ?? ''));
        $client->setEmail(mb_strtolower(trim($data['email'] ?? '')));
        $client->setCompany($company);

        $errors = $this->violationsToArray($this->validator->validate($client));
        if ($errors) {
            if (!$isNew) {
                $this->em->refresh($client);
            }

            return $this->errorResponse($errors);
        }

        $client->setLastActivity(new \DateTimeImmutable());

        $this->em->persist($client);
        $this->em->flush();

        return $this->json($client->toArray(), $isNew ? 201 : 200);
    }
}
