<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Annotation;

use Paysera\Bundle\ApiBundle\Entity\RestRequestOptions;
use Paysera\Bundle\ApiBundle\Service\Annotation\ReflectionMethodWrapper;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class ResponseNormalization implements RestAnnotationInterface
{
    /**
     * @var string|null
     */
    private $normalizationType;

    /**
     * @var string|null
     */
    private $normalizationGroup;

    public function __construct(array $options)
    {
        $this->setNormalizationType($options['normalizationType'] ?? null);
        $this->setNormalizationGroup($options['normalizationGroup'] ?? null);
    }

    private function setNormalizationType(?string $normalizationType): self
    {
        $this->normalizationType = $normalizationType;
        return $this;
    }

    public function setNormalizationGroup(?string $normalizationGroup): self
    {
        $this->normalizationGroup = $normalizationGroup;
        return $this;
    }

    public function isSeveralSupported(): bool
    {
        return false;
    }

    public function apply(RestRequestOptions $options, ReflectionMethodWrapper $reflectionMethod)
    {
        $options->setResponseNormalizationType($this->normalizationType);
        $options->setResponseNormalizationGroup($this->normalizationGroup);
    }
}
