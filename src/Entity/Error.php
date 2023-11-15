<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Entity;

class Error
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var int|null
     */
    private $statusCode;

    /**
     * @var string|null
     */
    private $uri;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var array|null
     */
    private $properties;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @var array
     */
    private $violations;

    public function __construct()
    {
        $this->violations = [];
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setStatusCode(?int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setUri(?string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setProperties(?array $properties): self
    {
        $this->properties = $properties;
        return $this;
    }

    public function getProperties(): ?array
    {
        return $this->properties;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return Violation[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param Violation[] $violations
     *
     * @return $this
     */
    public function setViolations(array $violations): self
    {
        $this->violations = $violations;
        return $this;
    }
}
