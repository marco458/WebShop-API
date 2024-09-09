<?php

declare(strict_types=1);

namespace Core\DataFixtures;

use Core\Fixtures\Story\CoreStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        CoreStory::load();

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 10;
    }
}
