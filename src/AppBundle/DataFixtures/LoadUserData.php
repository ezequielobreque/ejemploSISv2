<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 10/5/2019
 * Time: 8:48 PM
 */

namespace AppBundle\DataFixtures;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\User;

class LoadUserData Extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $EzequielO =new User();
        $EzequielO ->setUsername('Ezequiel');
        $password='test';
        $EzequielO->setPlainPassword($password);

        $EzequielO ->setEmail('ezequiel@gmail.com');
        $EzequielO ->setRoles(array('ROLE_ADMIN'));
        $EzequielO ->setEnabled(1);
        $manager->persist($EzequielO);


        $this->addReference('EzequielO',$EzequielO);

        $FacuT =new User();
        $FacuT ->setUsername('Facu');
        $FacuT ->setPlainPassword($password);
        $FacuT ->setEmail('facu@gmail.com');
        $FacuT ->setRoles(array('ROLE_ADMIN'));
        $FacuT ->setEnabled(1);
        $manager->persist($FacuT);


        $this->addReference('FacuT',$FacuT);

        $AbrilM =new User();
        $AbrilM ->setUsername('Abril');
        $AbrilM ->setPlainPassword($password);
        $AbrilM ->setEmail('abril@gmail.com');
        $AbrilM ->setRoles(array('ROLE_ADMIN'));
        $AbrilM ->setEnabled(1);
        $manager->persist($AbrilM);


        $this->addReference('AbrilM',$AbrilM);

        $SantiagoA =new User();
        $SantiagoA ->setUsername('Santiago');
        $SantiagoA ->setPlainPassword($password);
        $SantiagoA ->setEmail('santiago@gmail.com');
        $SantiagoA ->setRoles(array('ROLE_ADMIN'));
        $SantiagoA ->setEnabled(1);
        $manager->persist($SantiagoA);


        $this->addReference('SantiagoA',$SantiagoA);
        $manager->persist($EzequielO,$FacuT,$AbrilM,$SantiagoA);
        $manager->flush();
    }
    public function getOrder()
    {
      return 1;  // TODO: Implement getOrder() method.
    }


}