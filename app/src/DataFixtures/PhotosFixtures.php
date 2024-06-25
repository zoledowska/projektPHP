<?php

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\Users;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PhotosFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    private $photoFileDirectory;

    private $rootDir;

    private Filesystem $filesystem;

    public function __construct(string $rootDir, string $photoFileDirectory, Filesystem $filesystem)
    {
        $this->fixtureFileDirectory = $rootDir.'/public/fixture_images';
        $this->photoFileDirectory = $photoFileDirectory;
        $this->filesystem = $filesystem;
    }

    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        // Use Symfony Filesystem component to scan the fixtures_images directory
        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->in($this->fixtureFileDirectory)->files();

        $photoFiles = iterator_to_array($finder->getIterator());

        $this->createMany(100, 'photos', function (int $i) use ($photoFiles) {
            $photos = new Photos();
            $photos->setTitle($this->faker->word);
            $photos->setDescription($this->faker->sentence);
            $photos->setUploadDate(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $photos->setAlbum($this->getRandomReference('albums'));

            /** @var Users $author */
            $author = $this->getRandomReference('users');
            $photos->setAuthor($author);

            // Select a random file from fixtures_images directory
            $randomFile = $photoFiles[array_rand($photoFiles)];
            $fileName = $randomFile->getFilename();
            $this->filesystem->copy($randomFile, $this->photoFileDirectory.'/'.$fileName);
            $photos->setPhotoFilename($fileName); // Assuming you store just the filename

            return $photos;
        });

        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [AlbumsFixtures::class, UserFixtures::class];
    }
}
