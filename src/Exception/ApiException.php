<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Exception;

use Exception;
use Paysera\Bundle\ApiBundle\Entity\Violation;

class ApiException extends Exception
{
    const INVALID_REQUEST = 'invalid_request';
    const INVALID_PARAMETERS = 'invalid_parameters';
    const INVALID_STATE = 'invalid_state';
    const INVALID_GRANT = 'invalid_grant';
    const INVALID_CODE = 'invalid_code';
    const UNAUTHORIZED = 'unauthorized';
    const FORBIDDEN = 'forbidden';
    const NOT_FOUND = 'not_found';
    const RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';
    const INTERNAL_SERVER_ERROR = 'internal_server_error';
    const NOT_ACCEPTABLE = 'not_acceptable';
    const OFFSET_TOO_LARGE = 'offset_too_large';
    const INVALID_CURSOR = 'invalid_cursor';

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var int|null
     */
    private $statusCode;

    /**
     * @var array|null
     */
    private $properties;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @var Violation[]
     */
    private $violations;

    /**
     * @param string $errorCode
     * @param string|null $message
     * @param int|null $statusCode
     * @param Exception|null $previous
     * @param array|null $properties
     * @param array|null $data
     * @param array $violations
     */
    public function __construct(
        $errorCode,
        $message = null,
        $statusCode = null,
        Exception $previous = null,
        ?array $properties = null,
        ?array $data = null,
        array $violations = []
    ) {
        parent::__construct($message ?: '', 0, $previous);

        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->properties = $properties;
        $this->data = $data;
        $this->violations = $violations;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
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
