<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use App\Domain\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AllTeamsExistsByNamesValidator extends ConstraintValidator
{

    public function __construct(
        private readonly TeamRepository $teamRepository
    )
    {
    }

    /**
     * @param mixed $value
     * @param AllTeamsExistsByNames $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_array($value) || count($value) < 1) {
            return;
        }

        $targetTeams = $this->teamRepository->findBy(['name' => $value]);
        if (count($targetTeams) !== count($value)) {
            $foundTeamNames = array_map(function (Team $team): string {
                return $team->getName();
            }, $targetTeams);
            $formattedNotFoundNames = implode(', ',array_diff($foundTeamNames, $value));
            $this->context->buildViolation("{$constraint->message}:[{$formattedNotFoundNames}]")
                ->atPath('teamNames')
                ->addViolation();
        }
    }
}