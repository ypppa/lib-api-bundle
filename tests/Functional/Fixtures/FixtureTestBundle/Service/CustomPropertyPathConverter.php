<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Tests\Functional\Fixtures\FixtureTestBundle\Service;

use Paysera\Bundle\ApiBundle\Service\Validation\PropertyPathConverterInterface;

class CustomPropertyPathConverter implements PropertyPathConverterInterface
{
    public function convert(string $path): string
    {
        return 'prefixed:' . $path;
    }
}
