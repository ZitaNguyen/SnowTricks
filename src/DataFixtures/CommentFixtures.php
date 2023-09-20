<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class CommentFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(
        private readonly TrickRepository $trickRepository,
        private readonly UserRepository $userRepository
    ){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $users = $this->userRepository->findAll();
        $tricks = $this->trickRepository->findAll();

        foreach ($tricks as $trick) {
            for ($i = 0; $i < 10; $i++) {
                $comment = new Comment();
                $comment->setComment($this->faker->text(100))
                        ->setUserID($users[mt_rand(0, count($users) - 1)])
                        ->setTrickID($trick);

                $manager->persist($comment);
                $trick->addComment($comment);
            }
        }

        $manager->flush();
    }
}
