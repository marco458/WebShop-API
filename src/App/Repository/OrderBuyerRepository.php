<?php

namespace App\Repository;

use App\Entity\OrderBuyer;
use Core\Repository\BaseRepository;

class OrderBuyerRepository extends BaseRepository
{
    public const ENTITY_CLASS_NAME = OrderBuyer::class;
}
