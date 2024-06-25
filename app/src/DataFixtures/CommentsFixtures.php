<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class CommentsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Faker.
     */
    protected ?Generator $faker;

    /**
     * Persistence object manager.
     */
    // protected ?ObjectManager $manager;

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

        $this->createMany(100, 'comments', function (int $i) {
            $comment = new Comments();
            $comment->setContent($this->faker->sentence);
            $comment->setNick($this->faker->word);
            $comment->setEmail($this->faker->email);
            $comment->setPostDate(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );

            $comment->setPhoto($this->getRandomReference('photos'));

            return $comment;
        });

        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [PhotosFixtures::class];
    }
}
