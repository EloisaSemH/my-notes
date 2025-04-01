<?php

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NoteValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            'title' => [new Assert\NotBlank()],
            'content' => [new Assert\NotBlank()],
            'color' => [new Assert\Optional(), new Assert\Length(6), new Assert\Regex(['pattern' => '/^([0-9a-fA-F]{6})$/i'])],
        ]);

        $violations = $this->validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}