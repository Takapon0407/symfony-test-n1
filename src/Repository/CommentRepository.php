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

    /**
    * @return Comment[] Returns an array of Comment objects
    */
    public function findByUserId($userId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Comment[] Returns an array of Comment objects
    */
    public function findByUserIds($userIds): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $userIdsString = implode(',', $userIds);

        $sql = "
            SELECT * FROM user
            JOIN comment ON user.id = comment.user_id
            WHERE user.id IN ($userIdsString)
        ";
        
        $stmt = $conn->executeQuery($sql);
        
        $results = [];
        while ($row = $stmt->fetchAssociative()) {
            $results[] = $row;
        }
        
        return $results;
        
    }
}
