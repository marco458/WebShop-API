<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ProductRepository;

class ProductCollectionStateProvider implements ProviderInterface
{
    public function __construct(
        private ProductRepository $repository,
        private iterable $collectionExtensions,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->repository->getFilteredCollection($this->collectionExtensions, $context, $operation);
    }
}
