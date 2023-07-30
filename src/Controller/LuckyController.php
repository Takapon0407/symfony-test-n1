<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController
{
    #[Route('/lucky/number')]
    public function number(EntityManagerInterface $entityManager): Response
    {
        ini_set('memory_limit', '-1');
        $results = [];
        $repeatTimes = 1;
        $userCount=20000;
        $dataCount=$userCount*10;

        $firstStartTime = microtime(true);
        $users = $entityManager->getRepository(User::class)->findAllIds();
        $firstEndTime = microtime(true);
        $firstQueryTimeDiff = $firstEndTime - $firstStartTime;

        // N+1が発生しない場合
        for ($i = 0; $i < $repeatTimes; $i++) {
            $comments = [];
            $startTime = microtime(true);
            $result = $entityManager->getRepository(Comment::class)->findByUserIds($users);
            $endTime = microtime(true);
            $resultsHasNotN1[] = $endTime - $startTime;
        }

        // N+1が発生する場合
        for ($i = 0; $i < $repeatTimes; $i++) {
            $comments = [];
            $startTime = microtime(true);
            foreach ($users as $userId) {
                $comments[$userId] = $entityManager->getRepository(Comment::class)->findByUserId($userId);
            }
            $endTime = microtime(true);
            $resultsHasN1[] = $endTime - $startTime;
        }

        $resultsHasN1Text = implode('<br>', $resultsHasN1);
        $resultsHasNotN1Text = implode('<br>', $resultsHasNotN1);

        return new Response(
            '<html>
                <body>
                    初回分('.$userCount.'User取得)の処理時間(秒): <br>' . $firstQueryTimeDiff  . '<br><br>
                    Comment' . count($result) . '件取得分の処理時間(秒) - N+1有り: <br>' . $resultsHasN1Text .
                    '<br><br> Comment' . count($result) . '件取得分の処理時間(秒) - N+1無し: <br>' . $resultsHasNotN1Text . '
                </body>
            </html>'
        );
        
    }
}