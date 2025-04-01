<?php

namespace App\Validator;

use App\Validator\Constraints\UniqueEmail;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'name' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 255])],
            'email' => [new Assert\NotBlank(), new Assert\Email(), new UniqueEmail()],
            'password' => [new Assert\NotBlank(), new Assert\Length(['min' => 8, 'max' => 255])],
        ]);

        $violations = $this->validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}