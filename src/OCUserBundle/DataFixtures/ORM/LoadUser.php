<?php
/**
 * Created by PhpStorm.
 * User: melpe
 * Date: 15/01/2018
 * Time: 22:25
 */

namespace OCUserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OCUserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)

    {

        $listNames = array('Alexandre', 'Marine', 'Anna');
        $user = new User;
        $user->setUsername("Admin");
        $user->setPassword("Admin");
        $user->setSalt();
        $user->setRole(array('ROLE_ADMIN'));
        $manager->persist($user);

        foreach ($listNames as $name) {

            $user = new User;

            $user->setUsername($name);
            $user->setPassword($name);
            $user->setSalt('');
            $user->setRoles(array('ROLE_USER'));
            $manager->persist($user);
        }
        $manager->flush();
    }
}