<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Annotation;

use Paysera\Bundle\ApiBundle\Entity\RestRequestOptions;
use Paysera\Bundle\ApiBundle\Service\Annotation\ReflectionMethodWrapper;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class RequiredPermissions implements RestAnnotationInterface
{
    /**
     * @var array
     */
    private $permissions;

    public function __construct(array $options)
    {
        $this->setPermissions($options['permissions']);
    }

    private function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function isSeveralSupported(): bool
    {
        return true;
    }

    public function apply(RestRequestOptions $options, ReflectionMethodWrapper $reflectionMethod)
    {
        $options->setRequiredPermissions(
            array_unique(array_merge($options->getRequiredPermissions(), $this->permissions))
        );
    }
}
