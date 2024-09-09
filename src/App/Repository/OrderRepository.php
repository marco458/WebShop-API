<?php

namespace App\Repository;

use App\Entity\Order;
use Core\Repository\BaseRepository;

class OrderRepository extends BaseRepository
{
    public const ENTITY_CLASS_NAME = Order::class;
}
