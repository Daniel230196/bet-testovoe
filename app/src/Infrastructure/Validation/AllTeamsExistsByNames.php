<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AllTeamsExistsByNames extends Constraint
{
    public string $message = 'Не все команды существуют по именам';
}