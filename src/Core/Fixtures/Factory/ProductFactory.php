<?php

declare(strict_types=1);

namespace Core\Fixtures\Factory;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Product>
 *
 * @method static Product|Proxy                     createOne(array $attributes = [])
 * @method static array<Product>|array<Proxy>       createMany(int $number, array|callable $attributes = [])
 * @method static Product|Proxy                     find(object|array|mixed $criteria)
 * @method static Product|Proxy                     findOrCreate(array $attributes)
 * @method static Product|Proxy                     first(string $sortedField = 'id')
 * @method static Product|Proxy                     last(string $sortedField = 'id')
 * @method static Product|Proxy                     random(array $attributes = [])
 * @method static Product|Proxy                     randomOrCreate(array $attributes = [])
 * @method static array<Product>|array<Proxy>       all()
 * @method static array<Product>|array<Proxy>       findBy(array $attributes)
 * @method static array<Product>|array<Proxy>       randomSet(int $number, array $attributes = [])
 * @method static array<Product>|array<Proxy>       randomRange(int $min, int $max, array $attributes = [])
 * @method static ProductRepository|RepositoryProxy repository()
 * @method        Product|Proxy                     ecreate(array|callable $attributes = [])
 */
final class ProductFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
            'description' => self::faker()->sentence(),
            'price' => self::faker()->randomFloat(2, 10, 500),
            'sku' => self::faker()->uuid(),
            'published' => self::faker()->boolean(),
            'categories' => CategoryFactory::randomSet(2),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Product::class;
    }
}
