<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Service\Validation;

interface PropertyPathConverterInterface
{
    public function convert(string $path): string;
}
