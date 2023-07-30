<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Comment;
use Faker\Factory;

class UserCommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fakerJP = Factory::create('ja_JP');
        $faker = Factory::create();
        // 推奨はされないが、メモリ不足で検証できないため、リミット制限は一時的に解除
        ini_set('memory_limit', '-1');
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        // User 10人分
        for ($i = 0; $i < 10000; $i++) {
            $user = new User();
            $user->setName($fakerJP->name);
            $user->setAge($faker->numberBetween(5, 100));

            $manager->persist($user);
            $manager->flush();

            // UserごとComment 10件分
            for ($j = 0; $j < 10; $j++) {
                $comment = new Comment();

                $comment->setUserId($user->getId());
                $comment->setMessage($faker->word);

                $manager->persist($comment);

                // 20件ごとにflushとclearを行う
                if (($j+1) % 2 === 0) {  
                    $manager->flush();
                    $manager->clear();  // clear all managed entities
                }
            }

            // 最後にもう一度flushとclearを行う
            $manager->flush();
            $manager->clear();  // clear all managed entities
        }
    }
}
