<?php

namespace App\Repository;

use App\Entity\Category;
use Core\Repository\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public const ENTITY_CLASS_NAME = Category::class;
}
