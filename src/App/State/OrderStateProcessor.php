<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use App\Entity\OrderBuyer;
use App\Entity\OrderModifiers;
use App\Entity\Product;
use App\Repository\ContractListRepository;
use Carbon\Carbon;
use Core\Service\CurrentUserResolver;

class OrderStateProcessor implements ProcessorInterface
{
    public function __construct(
        private CurrentUserResolver $userResolver,
        private ContractListRepository $contractListRepository,
        private ProcessorInterface $persistProcessor,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        /** @var Order $order */
        $order = $data;
        $order->setCreatedAt(Carbon::now()->toDateTimeImmutable());
        $currentUser = $this->userResolver->resolve();

        /** @var OrderBuyer $orderBuyer */
        $orderBuyer = $order->getOrderBuyer();
        $orderBuyer->setMadeOrder($order);
        $orderBuyer->setOriginalUser($currentUser);

        $contractLists = $this->contractListRepository->findBy(['user' => $currentUser]);
        $contractProductPriceMap = [];
        foreach ($contractLists as $contractList) {
            $contractProductPriceMap[$contractList->getProduct()->getId()] = $contractList->getPrice();
        }

        $products = $order->getProducts();
        $totalPriceBeforeModifiers = 0;
        /** @var Product $product */
        foreach ($products as $product) {
            $totalPriceBeforeModifiers += $contractProductPriceMap[$product->getId()] ?? $product->getPrice();
        }
        $order->setPriceWithoutModifiers($totalPriceBeforeModifiers);

        $percentageFactor = 1;
        $modifiers = $order->getModifiers();
        /** @var OrderModifiers $modifier */
        foreach ($modifiers as $modifier) {
            if ($totalPriceBeforeModifiers >= $modifier->priceActivationThreshold()) {
                if ($modifier->isDiscount()) {
                    $percentageFactor *= (1 - $modifier->getPercentage() / 100);
                } else {
                    $percentageFactor *= (1 + $modifier->getPercentage() / 100);
                }
            }
        }

        $totalPrice = $totalPriceBeforeModifiers * $percentageFactor;
        $order->setFinalPrice($totalPrice);

        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
