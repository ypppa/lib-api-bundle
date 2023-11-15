<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Entity;

class Violation
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
