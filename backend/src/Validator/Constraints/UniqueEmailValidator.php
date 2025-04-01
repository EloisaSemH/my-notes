<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new \LogicException('Use only UniqueEmail Constraint.');
        }

        if ($value === null || $value === '') {
            return;
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if ($user !== null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}