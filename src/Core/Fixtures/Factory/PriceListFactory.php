<?php

declare(strict_types=1);

namespace Core\Fixtures\Factory;

use App\Entity\PriceList;
use App\Repository\PriceListRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PriceList>
 *
 * @method static PriceList|Proxy                     createOne(array $attributes = [])
 * @method static array<PriceList>|array<Proxy>       createMany(int $number, array|callable $attributes = [])
 * @method static PriceList|Proxy                     find(object|array|mixed $criteria)
 * @method static PriceList|Proxy                     findOrCreate(array $attributes)
 * @method static PriceList|Proxy                     first(string $sortedField = 'id')
 * @method static PriceList|Proxy                     last(string $sortedField = 'id')
 * @method static PriceList|Proxy                     random(array $attributes = [])
 * @method static PriceList|Proxy                     randomOrCreate(array $attributes = [])
 * @method static array<PriceList>|array<Proxy>       all()
 * @method static array<PriceList>|array<Proxy>       findBy(array $attributes)
 * @method static array<PriceList>|array<Proxy>       randomSet(int $number, array $attributes = [])
 * @method static array<PriceList>|array<Proxy>       randomRange(int $min, int $max, array $attributes = [])
 * @method static PriceListRepository|RepositoryProxy repository()
 * @method        PriceList|Proxy                     ecreate(array|callable $attributes = [])
 */
final class PriceListFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->word(),
            'price' => self::faker()->randomFloat(4, 10, 500),
            'sku' => self::faker()->uuid(),
            'product' => ProductFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return PriceList::class;
    }
}
