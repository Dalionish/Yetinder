<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class UniqueValue extends Constraint
{
    public string $message = 'Tento email je již zaregistrovaný!';
}