<?php
/**
 * Photos fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Photos;
use App\Entity\Users;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class PhotosFixtures.
 *
 * Loads photo data into the database for testing and development.
 */
class PhotosFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Directory for photo files.
     */
    private string $photoFileDirectory;

    /**
     * Root directory.
     */
    private string $fixtureFileDirectory;

    /**
     * Filesystem component.
     */
    private Filesystem $filesystem;

    /**
     * Constructor.
     *
     * @param string     $rootDir            the root directory path
     * @param string     $photoFileDirectory the directory where photo files are stored
     * @param Filesystem $filesystem         the filesystem component for handling files
     */
    public function __construct(string $rootDir, string $photoFileDirectory, Filesystem $filesystem)
    {
        $this->fixtureFileDirectory = $rootDir.'/public/fixture_images';
        $this->photoFileDirectory = $photoFileDirectory;
        $this->filesystem = $filesystem;
    }

    /**
     * Load data.
     *
     * This method is used to load initial photo data into the database.
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        // Use Symfony Filesystem component to scan the fixtures_images directory
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

    /**
     * Get dependencies.
     *
     * This method returns the classes of fixtures that this fixture depends on.
     *
     * @return array an array of fixture classes
     */
    public function getDependencies(): array
    {
        return [AlbumsFixtures::class, UserFixtures::class];
    }
}
