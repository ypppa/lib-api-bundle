<?php
declare(strict_types=1);

namespace Paysera\Bundle\ApiBundle\Service;

use Paysera\Bundle\ApiBundle\Entity\RestRequestOptions;

/**
 * @internal
 */
class RestRequestOptionsRegistry
{
    /**
     * @var RestRequestOptions[] associative array
     */
    private $restRequestOptionsByController;

    public function __construct()
    {
        $this->restRequestOptionsByController = [];
    }

    public function registerRestRequestOptions(RestRequestOptions $options, string $controller)
    {
        $this->restRequestOptionsByController[$controller] = $options;
    }

    public function getRestRequestOptionsForController(string $controller): ?RestRequestOptions
    {
        return $this->restRequestOptionsByController[$controller] ?? null;
    }
}
