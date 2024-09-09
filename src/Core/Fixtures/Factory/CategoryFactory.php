<?php

declare(strict_types=1);

namespace Core\Fixtures\Factory;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Category>
 *
 * @method static Category|Proxy                     createOne(array $attributes = [])
 * @method static array<Category>|array<Proxy>       createMany(int $number, array|callable $attributes = [])
 * @method static Category|Proxy                     find(object|array|mixed $criteria)
 * @method static Category|Proxy                     findOrCreate(array $attributes)
 * @method static Category|Proxy                     first(string $sortedField = 'id')
 * @method static Category|Proxy                     last(string $sortedField = 'id')
 * @method static Category|Proxy                     random(array $attributes = [])
 * @method static Category|Proxy                     randomOrCreate(array $attributes = [])
 * @method static array<Category>|array<Proxy>       all()
 * @method static array<Category>|array<Proxy>       findBy(array $attributes)
 * @method static array<Category>|array<Proxy>       randomSet(int $number, array $attributes = [])
 * @method static array<Category>|array<Proxy>       randomRange(int $min, int $max, array $attributes = [])
 * @method static CategoryRepository|RepositoryProxy repository()
 * @method        Category|Proxy                     ecreate(array|callable $attributes = [])
 */
final class CategoryFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
            'description' => self::faker()->sentence(),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Category::class;
    }
}
