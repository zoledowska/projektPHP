<?php

namespace App\DataFixtures;

use App\Entity\Albums;

/**
 * Class AlbumsFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AlbumsFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'albums', function (int $i) {
            $albums = new Albums();
            $albums->setName($this->faker->unique()->word);
            $albums->setDescription($this->faker->sentence);

            return $albums;
        });

        $this->manager->flush();
    }
}