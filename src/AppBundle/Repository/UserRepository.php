<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Created by PhpStorm.
 * User: Mariukas
 * Date: 2016.11.14
 * Time: 15:30
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
        // TODO: Implement loadUserByUsername() method.
    }

}