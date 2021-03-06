<?php

declare(strict_types=1);

namespace Sylius\RefundPlugin\Refunder;

use Prooph\ServiceBus\EventBus;
use Sylius\RefundPlugin\Creator\RefundCreatorInterface;
use Sylius\RefundPlugin\Event\ShipmentRefunded;
use Sylius\RefundPlugin\Model\RefundType;
use Sylius\RefundPlugin\Model\ShipmentRefund;

final class OrderShipmentsRefunder implements RefunderInterface
{
    /** @var RefundCreatorInterface */
    private $refundCreator;

    /** @var EventBus */
    private $eventBus;

    public function __construct(RefundCreatorInterface $refundCreator, EventBus $eventBus)
    {
        $this->refundCreator = $refundCreator;
        $this->eventBus = $eventBus;
    }

    public function refundFromOrder(array $units, string $orderNumber): int
    {
        $refundedTotal = 0;

        /** @var ShipmentRefund $shipmentUnit */
        foreach ($units as $shipmentUnit) {
            $this->refundCreator->__invoke($orderNumber, $shipmentUnit->id(), $shipmentUnit->total(), RefundType::shipment());

            $refundedTotal += $shipmentUnit->total();

            $this->eventBus->dispatch(new ShipmentRefunded($orderNumber, $shipmentUnit->id(), $shipmentUnit->total()));
        }

        return $refundedTotal;
    }
}
