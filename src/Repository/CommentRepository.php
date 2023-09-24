<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByUserId($userId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM comment c
            WHERE c.user_id = :user_id
            ORDER BY c.id ASC
        ';
        
        $stmt = $conn->executeQuery($sql, ['user_id' => $userId]);

        return $stmt->fetchAllAssociative();
    }

    /**
    * @return Comment[] Returns an array of Comment objects
    */
    public function findByUserIds($userIds): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $placeholders = implode(',', array_fill(0, count($userIds), '?'));

        $sql = "
            SELECT * FROM user
            JOIN comment ON user.id = comment.user_id
            WHERE user.id IN ($placeholders)
        ";

        $stmt = $conn->executeQuery($sql, $userIds);
        
        $results = [];
        while ($row = $stmt->fetchAssociative()) {
            $results[] = $row;
        }
        
        return $results;
    }
}
