<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Annotation;

use Paysera\Bundle\ApiBundle\Entity\PathAttributeResolverOptions;
use Paysera\Bundle\ApiBundle\Entity\RestRequestOptions;
use Paysera\Bundle\ApiBundle\Exception\ConfigurationException;
use Paysera\Bundle\ApiBundle\Service\Annotation\ReflectionMethodWrapper;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class PathAttribute implements RestAnnotationInterface
{
    /**
     * @var string
     */
    private $parameterName;

    /**
     * @var string
     */
    private $pathPartName;

    /**
     * @var string|null
     */
    private $resolverType;

    /**
     * @var bool|null
     */
    private $resolutionMandatory;

    public function __construct(array $options)
    {
        $this->setParameterName($options['parameterName']);
        $this->setPathPartName($options['pathPartName']);
        $this->setResolverType($options['resolverType'] ?? null);
        $this->setResolutionMandatory($options['resolutionMandatory'] ?? null);
    }

    private function setParameterName(string $parameterName): self
    {
        $this->parameterName = $parameterName;
        return $this;
    }

    private function setPathPartName(string $pathPartName): self
    {
        $this->pathPartName = $pathPartName;
        return $this;
    }

    private function setResolverType(?string $resolverType): self
    {
        $this->resolverType = $resolverType;
        return $this;
    }

    private function setResolutionMandatory(?bool $resolutionMandatory): self
    {
        $this->resolutionMandatory = $resolutionMandatory;
        return $this;
    }

    public function isSeveralSupported(): bool
    {
        return true;
    }

    public function apply(RestRequestOptions $options, ReflectionMethodWrapper $reflectionMethod)
    {
        $options->addPathAttributeResolverOptions(
            (new PathAttributeResolverOptions())
                ->setParameterName($this->parameterName)
                ->setPathPartName($this->pathPartName)
                ->setPathAttributeResolverType($this->resolvePathAttributeResolverType($reflectionMethod))
                ->setResolutionMandatory($this->resolveIfResolutionIsMandatory($reflectionMethod))
        );
    }

    private function resolvePathAttributeResolverType(ReflectionMethodWrapper $reflectionMethod): string
    {
        if ($this->resolverType !== null) {
            return $this->resolverType;
        }

        try {
            return $reflectionMethod->getNonBuiltInTypeForParameter($this->parameterName);
        } catch (ConfigurationException $exception) {
            throw new ConfigurationException(sprintf(
                'Denormalization type could not be guessed for %s in %s',
                '$' . $this->parameterName,
                $reflectionMethod->getFriendlyName()
            ));
        }
    }

    private function resolveIfResolutionIsMandatory(ReflectionMethodWrapper $reflectionMethod): bool
    {
        if ($this->resolutionMandatory !== null) {
            return $this->resolutionMandatory;
        }

        $parameter = $reflectionMethod->getParameterByName($this->parameterName);

        return !$parameter->isDefaultValueAvailable();
    }
}
