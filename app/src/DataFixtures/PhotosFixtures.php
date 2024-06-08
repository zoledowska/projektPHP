<?php

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\Albums;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class PhotosFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Faker.
     */
    protected ?Generator $faker;

    /**
     * Persistence object manager.
     */
    //protected ?ObjectManager $manager;

    /**
     * Load.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'photos', function (int $i) {
            $photos = new Photos();
            $photos->setTitle($this->faker->word);
            $photos->setDescription($this->faker->sentence);
            $photos->setUploadDate(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );

            $photos->setPhotoPath(0);
            //$photos->setAlbum($album);
            $photos->setAlbum($this->getRandomReference('albums'));

            return $photos;
        });

        //$album = $this->getRandomReference('albums');
        //print_r($album);
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [AlbumsFixtures::class];
    }
}
