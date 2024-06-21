<?php

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\Albums;
use App\Entity\Users;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
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
            # dump($photos->getAlbum()->getName());

            /** @var Users $author */
            $author = $this->getRandomReference('users');
            $photos->setAuthor($author);
            
            return $photos;
        });

        //$album = $this->getRandomReference('albums');
        //print_r($album);
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [AlbumsFixtures::class, UserFixtures::class];
    }
}
