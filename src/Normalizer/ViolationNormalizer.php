<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Normalizer;

use Paysera\Bundle\ApiBundle\Entity\Violation;
use Paysera\Component\Normalization\NormalizationContext;
use Paysera\Component\Normalization\NormalizerInterface;
use Paysera\Component\Normalization\TypeAwareInterface;

class ViolationNormalizer implements NormalizerInterface, TypeAwareInterface
{
    /**
     * @param Violation $entity
     * @param NormalizationContext $normalizationContext
     *
     * @return array
     */
    public function normalize($entity, NormalizationContext $normalizationContext): array
    {
        return [
            'code' => $entity->getCode(),
            'message' => $entity->getMessage(),
            'field' => $entity->getField(),
        ];
    }

    public function getType(): string
    {
        return Violation::class;
    }
}
