<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('test');

            $users[] = $user;
            $manager->persist($user);
        }

        // Groups
        $groups = [];
        for ($i = 0; $i < 5; $i++) {
            $group = new Group();
            $group->setName($this->faker->word());

            $groups[] = $group;
            $manager->persist($group);
        }

        // Tricks
        $tricks = [];
        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setName($this->faker->word())
                ->setSlug($this->faker->slug())
                ->setDescription($this->faker->text(300))
                ->setUser($users[mt_rand(0, count($users) - 1)])
                ->setGroup($groups[mt_rand(0, count($groups) - 1)]);

            $tricks[] = $trick;
            $manager->persist($trick);
        }

        // Comments
        foreach ($tricks as $trick) {
            for ($i = 0; $i < 10; $i++) {
                $comment = new Comment();
                $comment->setComment($this->faker->text(100))
                        ->setUser($users[mt_rand(0, count($users) - 1)])
                        ->setTrick($trick);

                $manager->persist($comment);
                $trick->addComment($comment);
            }
        }

        $manager->flush();
    }
}
