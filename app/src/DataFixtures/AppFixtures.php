<?php
/**
 * App fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 *
 * Loads initial data into the database for testing and development.
 */
class AppFixtures extends Fixture
{
    /**
     * Load data.
     *
     * This method is used to load initial data into the database.
     *
     * @param ObjectManager $manager the entity manager
     */
    public function load(ObjectManager $manager): void
    {
        // Example data loading:
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
