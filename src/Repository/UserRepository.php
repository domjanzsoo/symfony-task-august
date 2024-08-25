<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;

        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

   /**
    * @return User[] Returns an array of User objects
    */
   public function getAllQuery(string $search = null)
   {
       $query = $this->createQueryBuilder('u');

        if (isset($search)) {
            $query->where('u.username LIKE :val')
                ->orWhere('u.email LIKE :val')
                ->orWhere('u.currentBase LIKE :val')
                ->setParameter('val', '%' . $search . '%');
        }

        if (!$this->security->isGranted('ROLE_ABSOLUTE_ACCESS') && !$this->security->isGranted('ROLE_MANAGE_USER_PLUS')) {
            $query->andWhere("JSON_CONTAINS(u.roles, '\"ROLE_ABSOLUTE_ACCESS\"', '$') = false");
        }

        // dd($query->getQuery());

        return $query->orderBy('u.id', 'ASC')->getQuery();
   }

   public function deleteUsers(array $userIds): void
   {
        $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id IN (:ids)')
            ->getQuery()
            ->execute(['ids' => $userIds]);

        return;
   }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
