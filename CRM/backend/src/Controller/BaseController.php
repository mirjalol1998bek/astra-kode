<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class BaseController extends AbstractController
{
    protected function getBody(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        return is_array($data) ? $data : [];
    }

    protected function errorResponse(array $errors): JsonResponse
    {
        return $this->json(['errors' => $errors], 422);
    }

    protected function violationsToArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $field = $violation->getPropertyPath();
            if (!isset($errors[$field])) {
                $errors[$field] = $violation->getMessage();
            }
        }

        return $errors;
    }

    protected function checkPassword(?string $password, bool $required): ?string
    {
        if ($password === null || $password === '') {
            return $required ? 'Parolni kiriting' : null;
        }

        if (mb_strlen($password) < 6) {
            return "Parol kamida 6 ta belgidan iborat bo'lishi kerak";
        }

        return null;
    }
}
