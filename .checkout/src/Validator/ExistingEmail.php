<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ExistingEmail extends Constraint
{
    public string $message = 'Tento email není zaregistrovaný!';
}