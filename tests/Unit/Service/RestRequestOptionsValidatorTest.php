<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Tests\Unit\Service;

use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Paysera\Bundle\ApiBundle\Entity\PathAttributeResolverOptions;
use Paysera\Bundle\ApiBundle\Entity\QueryResolverOptions;
use Paysera\Bundle\ApiBundle\Entity\RestRequestOptions;
use Paysera\Bundle\ApiBundle\Exception\ConfigurationException;
use Paysera\Bundle\ApiBundle\Service\PathAttributeResolver\PathAttributeResolverInterface;
use Paysera\Bundle\ApiBundle\Service\PathAttributeResolver\PathAttributeResolverRegistry;
use Paysera\Bundle\ApiBundle\Service\RestRequestOptionsValidator;
use Paysera\Component\Normalization\NormalizerRegistryInterface;

class RestRequestOptionsValidatorTest extends MockeryTestCase
{
    /**
     * @dataProvider provideDataForValidateRestRequestOptions
     *
     * @param bool $expectException
     * @param RestRequestOptions $options
     * @throws ConfigurationException
     */
    public function testValidateRestRequestOptions(bool $expectException, RestRequestOptions $options)
    {
        /** @var MockInterface|NormalizerRegistryInterface $normalizerRegistryMock */
        $normalizerRegistryMock = Mockery::mock(NormalizerRegistryInterface::class);
        /** @var MockInterface|PathAttributeResolverRegistry $pathAttributeRegistryMock */
        $pathAttributeRegistryMock = Mockery::mock(PathAttributeResolverRegistry::class);
        $validator = new RestRequestOptionsValidator(
            $normalizerRegistryMock,
            $pathAttributeRegistryMock
        );

        $normalizerRegistryMock
            ->allows('hasNormalizer')
            ->with('non_existing_normalizer')
            ->andReturns(false)
        ;
        $normalizerRegistryMock
            ->allows('hasNormalizer')
            ->with('existing_normalizer')
            ->andReturns(true)
        ;
        $normalizerRegistryMock
            ->allows('getDenormalizerType')
            ->with('non_existing_denormalizer')
            ->andReturns(NormalizerRegistryInterface::DENORMALIZER_TYPE_NONE)
        ;
        $normalizerRegistryMock
            ->allows('getDenormalizerType')
            ->with('object_denormalizer')
            ->andReturns(NormalizerRegistryInterface::DENORMALIZER_TYPE_OBJECT)
        ;
        $normalizerRegistryMock
            ->allows('getDenormalizerType')
            ->with('mixed_type_denormalizer')
            ->andReturns(NormalizerRegistryInterface::DENORMALIZER_TYPE_MIXED)
        ;

        $pathAttributeRegistryMock
            ->allows('getResolverByType')
            ->with('non_existing_path_attribute_resolver')
            ->andThrow(new InvalidArgumentException())
        ;
        $pathAttributeRegistryMock
            ->allows('getResolverByType')
            ->with('existing_path_attribute_resolver')
            ->andReturns(Mockery::mock(PathAttributeResolverInterface::class))
        ;

        if ($expectException) {
            $this->expectException(ConfigurationException::class);
        }

        $validator->validateRestRequestOptions($options, 'MyClass::method');

        if (!$expectException) {
            $this->addToAssertionCount(1);
        }
    }

    public function provideDataForValidateRestRequestOptions()
    {
        return [
            [
                false,
                (new RestRequestOptions())
                    ->setResponseNormalizationType('existing_normalizer')
                ,
            ],
            [
                true,
                (new RestRequestOptions())
                    ->setResponseNormalizationType('non_existing_normalizer')
                ,
            ],
            [
                false,
                (new RestRequestOptions())
                    ->setBodyDenormalizationType('object_denormalizer')
                    ->setBodyParameterName('body')
                ,
            ],
            [
                false,
                (new RestRequestOptions())
                    ->setBodyDenormalizationType('mixed_type_denormalizer')
                    ->setBodyParameterName('body')
                ,
            ],
            [
                true,
                (new RestRequestOptions())
                    ->setBodyDenormalizationType('non_existing_denormalizer')
                    ->setBodyParameterName('body')
                ,
            ],
            [
                true,
                (new RestRequestOptions())
                    ->setBodyDenormalizationType('non_existing_denormalizer')
                    ->setBodyParameterName('body')
                    ->setBodyOptional(true)
                ,
            ],
            [
                false,
                (new RestRequestOptions())->addQueryResolverOptions(
                    (new QueryResolverOptions())
                        ->setDenormalizationType('object_denormalizer')
                ),
            ],
            [
                false,
                (new RestRequestOptions())->addQueryResolverOptions(
                    (new QueryResolverOptions())
                        ->setDenormalizationType('mixed_type_denormalizer')
                ),
            ],
            [
                true,
                (new RestRequestOptions())->addQueryResolverOptions(
                    (new QueryResolverOptions())
                        ->setDenormalizationType('non_existing_denormalizer')
                ),
            ],
            [
                true,
                (new RestRequestOptions())->addQueryResolverOptions(
                    (new QueryResolverOptions())
                        ->setDenormalizationType('object_denormalizer')
                )->addQueryResolverOptions(
                    (new QueryResolverOptions())
                        ->setDenormalizationType('non_existing_denormalizer')
                ),
            ],
            [
                false,
                (new RestRequestOptions())->addPathAttributeResolverOptions(
                    (new PathAttributeResolverOptions())
                        ->setPathAttributeResolverType('existing_path_attribute_resolver')
                ),
            ],
            [
                true,
                (new RestRequestOptions())->addPathAttributeResolverOptions(
                    (new PathAttributeResolverOptions())
                        ->setPathAttributeResolverType('non_existing_path_attribute_resolver')
                ),
            ],
            [
                true,
                (new RestRequestOptions())->addPathAttributeResolverOptions(
                    (new PathAttributeResolverOptions())
                        ->setPathAttributeResolverType('existing_path_attribute_resolver')
                )->addPathAttributeResolverOptions(
                    (new PathAttributeResolverOptions())
                        ->setPathAttributeResolverType('non_existing_path_attribute_resolver')
                ),
            ],
        ];
    }
}
