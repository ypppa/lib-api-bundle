<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Service\Validation;

class CamelCaseToSnakeCaseConverter implements PropertyPathConverterInterface
{
    public function convert(string $path): string
    {
        return ltrim(
            mb_strtolower(
                preg_replace(
                    '/[A-Z]/u',
                    '_$0',
                    $path
                )
            ),
            '_'
        );
    }
}
