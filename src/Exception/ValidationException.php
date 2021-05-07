<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends AppException
{
    public function __construct(string $className, array $inputData, ConstraintViolationListInterface $errorList)
    {
        parent::__construct('Validation error!');

        $this->type = 'VALIDATION_ERROR';
        $this->httpCode = Response::HTTP_BAD_REQUEST;

        $errors = [];

        /** @var ConstraintViolationInterface $error */
        foreach ($errorList as $error) {
            $errors[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }

        $this->parameters = [
            'model' => $this->stripNamespaceFromClassName($className),
            'validationErrors' => $errors,
            'inputData' => $inputData,
        ];
    }
}
