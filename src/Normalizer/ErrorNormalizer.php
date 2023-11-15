<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Normalizer;

use Paysera\Bundle\ApiBundle\Entity\Error;
use Paysera\Component\Normalization\NormalizationContext;
use Paysera\Component\Normalization\NormalizerInterface;
use Paysera\Component\Normalization\TypeAwareInterface;

class ErrorNormalizer implements NormalizerInterface, TypeAwareInterface
{
    /**
     * @param Error $entity
     * @param NormalizationContext $normalizationContext
     *
     * @return array
     */
    public function normalize($entity, NormalizationContext $normalizationContext): array
    {
        $normalizationContext->markNullValuesForRemoval();
        return [
            'error' => $entity->getCode(),
            'error_description' => $entity->getMessage(),
            'error_uri' => $entity->getUri(),
            'error_properties' => $entity->getProperties(),
            'error_data' => $entity->getData(),
            'errors' => $entity->getViolations() !== [] ? $entity->getViolations() : null,
        ];
    }

    public function getType(): string
    {
        return Error::class;
    }
}
