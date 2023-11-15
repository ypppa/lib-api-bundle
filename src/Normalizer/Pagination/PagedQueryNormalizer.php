<?php

declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Normalizer\Pagination;

use Paysera\Bundle\ApiBundle\Entity\PagedQuery;
use Paysera\Component\Normalization\NormalizationContext;
use Paysera\Component\Normalization\NormalizerInterface;
use Paysera\Component\Normalization\TypeAwareInterface;
use Paysera\Pagination\Entity\Result;
use Paysera\Pagination\Service\Doctrine\ResultProvider;

/**
 * @see ResultNormalizer for fetched result normalization
 */
class PagedQueryNormalizer implements NormalizerInterface, TypeAwareInterface
{
    private $resultProvider;
    private $defaultTotalCountStrategy;
    private $maximumOffset;

    public function __construct(ResultProvider $resultProvider, string $defaultTotalCountStrategy, ?int $maximumOffset)
    {
        $this->resultProvider = $resultProvider;
        $this->defaultTotalCountStrategy = $defaultTotalCountStrategy;
        $this->maximumOffset = $maximumOffset;
    }

    /**
     * @param PagedQuery $entity
     * @param NormalizationContext $normalizationContext
     *
     * @return array
     */
    public function normalize($entity, NormalizationContext $normalizationContext)
    {
        $result = $this->fetchResultByPagedQuery($entity, $normalizationContext);
        return $normalizationContext->normalize($result, '');
    }

    private function fetchResultByPagedQuery(PagedQuery $pagedQuery, NormalizationContext $normalizationContext): Result
    {
        $configuredQuery = clone $pagedQuery->getConfiguredQuery();

        if (
            !$normalizationContext->isFieldIncluded('items')
            && !$normalizationContext->isFieldIncluded('_metadata.has_next')
            && !$normalizationContext->isFieldIncluded('_metadata.has_previous')
            && !$normalizationContext->isFieldIncluded('cursors')
        ) {
            $totalCount = $this->resultProvider->getTotalCountForQuery($configuredQuery);
            return (new Result())
                ->setTotalCount($totalCount)
            ;
        }

        $strategy = $pagedQuery->getTotalCountStrategy();
        if ($strategy === PagedQuery::TOTAL_COUNT_STRATEGY_DEFAULT) {
            $strategy = $this->defaultTotalCountStrategy;
        }
        $totalIncluded = (
            $strategy === PagedQuery::TOTAL_COUNT_STRATEGY_ALWAYS
            && $normalizationContext->isFieldIncluded('_metadata.total')
        ) || (
            $strategy === PagedQuery::TOTAL_COUNT_STRATEGY_OPTIONAL
            && $normalizationContext->isFieldExplicitlyIncluded('_metadata.total')
        );

        $configuredQuery->setTotalCountNeeded($totalIncluded);

        if (
            !$configuredQuery->hasMaximumOffset()
            && $this->maximumOffset !== null
            && $pagedQuery->getTotalCountStrategy() !== PagedQuery::TOTAL_COUNT_STRATEGY_ALWAYS
        ) {
            $configuredQuery->setMaximumOffset($this->maximumOffset);
        }

        return $this->resultProvider->getResultForQuery($configuredQuery, $pagedQuery->getPager());
    }

    public function getType(): string
    {
        return PagedQuery::class;
    }
}
