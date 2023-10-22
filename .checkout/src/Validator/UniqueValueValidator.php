<?php

namespace App\Validator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueValueValidator extends ConstraintValidator
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function validate($value, Constraint $constraint): void
    {
        $query = $this->connection->createQueryBuilder()
            ->select('COUNT(userID)')
            ->from('user')
            ->where('email = :value')
            ->setParameter('value', $value);
        $count = null;
        try {
            $count = $query->fetchOne();
        } catch (Exception $e) {
            echo "error: " . $e->getMessage();
        }

        if ($count > 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}