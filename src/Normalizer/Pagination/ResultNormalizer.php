<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Normalizer\Pagination;

use Paysera\Pagination\Entity\Result;
use Paysera\Component\Normalization\NormalizationContext;
use Paysera\Component\Normalization\NormalizerInterface;
use Paysera\Component\Normalization\TypeAwareInterface;

/**
 * @see PagedQueryNormalizer should be used to optimize queries depending on requested fields
 */
class ResultNormalizer implements NormalizerInterface, TypeAwareInterface
{
    /**
     * @param Result $entity
     * @param NormalizationContext $normalizationContext
     *
     * @return array
     */
    public function normalize($entity, NormalizationContext $normalizationContext): array
    {
        return [
            'items' => $entity->getItems(),
            '_metadata' => $this->mapMetadataFromEntity($entity),
        ];
    }

    private function mapMetadataFromEntity(Result $result): array
    {
        $data = [
            'total' => $result->getTotalCount(),
            'has_next' => $result->hasNext(),
            'has_previous' => $result->hasPrevious(),
        ];

        if ($result->getNextCursor() !== null) {
            $data['cursors']['after'] = $result->getNextCursor();
        }
        if ($result->getPreviousCursor() !== null) {
            $data['cursors']['before'] = $result->getPreviousCursor();
        }

        return $data;
    }

    public function getType(): string
    {
        return Result::class;
    }
}
