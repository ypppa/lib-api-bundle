<?php

declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Tests\Unit\Serializer;

use JsonSerializable;
use RuntimeException;

if (phpversion() >= 8) {
    class JsonSerializerExtension implements JsonSerializable
    {
        public function jsonSerialize(): mixed
        {
            throw new RuntimeException('expected');
        }
    }
} else {
    class JsonSerializerExtension implements JsonSerializable
    {
        public function jsonSerialize()
        {
            throw new RuntimeException('expected');
        }
    }
}

