<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use App\Application\Spi\TeamRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TeamNotExistsByNameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository
    ) {
    }

    /**
     * @param TeamNotExistsByName $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        $existingTeam = $this->teamRepository->findOneBy(['name' => (string)$value]);
        if ($existingTeam !== null) {
            $this->context->buildViolation($constraint->message)
                ->atPath('name')
                ->addViolation();
        }
    }
}