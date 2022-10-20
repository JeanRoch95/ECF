<?php

namespace App\Repository;

use App\Entity\UserPartenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<UserPartenaire>
 *
 * @method UserPartenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPartenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPartenaire[]    findAll()
 * @method UserPartenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPartenaireRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPartenaire::class);
    }

    public function add(UserPartenaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserPartenaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof UserPartenaire) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function getPaginatedPart($filter){
        $query = $this->createQueryBuilder('a');
            $query
                ->where('a.status = 0 OR a.status = 1');

            if($filter !== null ){
                $query->andWhere('a.status = :PART')
                    ->setParameter(':PART', $filter);
            }
            $query
                ->orderBy('a.partenaireName');
        return $query->getQuery()->getResult();
    }

    public function getTotalPart($filter){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.status = 0 OR a.status = 1');
        if($filter !== null ){
            $query->andWhere('a.status IN(:PARTS)')
                ->setParameter(':PARTS', $filter);
        }

        return $query->getQuery()->getSingleScalarResult();
    }



    public function findBySearch($search): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :val')
            ->setParameter('val', '%'.$search.'%')
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return UserPartenaire[] Returns an array of UserPartenaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserPartenaire
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
