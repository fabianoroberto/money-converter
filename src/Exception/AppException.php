<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class AppException extends \Exception
{
    protected string $type;

    protected array $parameters;

    protected int $httpCode;

    public function __construct($message, $parameters = [])
    {
        parent::__construct($message);

        $this->type = 'APP_ERROR';
        $this->httpCode = Response::HTTP_BAD_REQUEST;
        $this->parameters = $parameters;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isCritical(): bool
    {
        return false;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    protected function stripNamespaceFromClassName(string $className): string
    {
        return substr($className, strrpos($className, '\\') - \strlen($className) + 1);
    }
}
