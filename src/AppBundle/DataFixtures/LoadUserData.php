<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 10/5/2019
 * Time: 8:48 PM
 */

namespace AppBundle\DataFixtures;
use AppBundle\Entity\Portada;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\User;

class LoadUserData Extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $portadaEO=new Portada();
        $EzequielO =new User();
        $EzequielO ->setUsername('Ezequiel');
        $password='test';
        $EzequielO->setPlainPassword($password);


        $EzequielO ->setEmail('ezequiel@gmail.com');
        $EzequielO ->setRoles(array('ROLE_ADMIN'));
        $EzequielO ->setEnabled(1);
        $EzequielO->setPortada($portadaEO);
        $portadaEO->setUser($EzequielO);
        $manager->persist($EzequielO);
        $manager->persist($portadaEO);

        $this->addReference('EzequielO',$EzequielO);
        $portadaFT=new Portada();
        $FacuT =new User();
        $FacuT ->setUsername('Facu');
        $FacuT ->setPlainPassword($password);
        $FacuT ->setEmail('facu@gmail.com');
        $FacuT ->setRoles(array('ROLE_ADMIN'));
        $FacuT ->setEnabled(1);
        $FacuT->setPortada($portadaFT);
        $portadaFT->setUser($FacuT);
        $manager->persist($FacuT);
        $manager->persist($portadaFT);


        $this->addReference('FacuT',$FacuT);
        $portadaAM=new Portada();
        $AbrilM =new User();
        $AbrilM ->setUsername('Abril');
        $AbrilM ->setPlainPassword($password);
        $AbrilM ->setEmail('abril@gmail.com');
        $AbrilM ->setRoles(array('ROLE_ADMIN'));
        $AbrilM ->setEnabled(1);
        $AbrilM->setPortada($portadaAM);
        $portadaAM->setUser($AbrilM);
        $manager->persist($AbrilM);
        $manager->persist($portadaAM);


        $this->addReference('AbrilM',$AbrilM);
        $portadaSA=new Portada();
        $SantiagoA =new User();
        $SantiagoA ->setUsername('Santiago');
        $SantiagoA ->setPlainPassword($password);
        $SantiagoA ->setEmail('santiago@gmail.com');
        $SantiagoA ->setRoles(array('ROLE_ADMIN'));
        $SantiagoA ->setEnabled(1);
        $SantiagoA->setPortada($portadaSA);
        $portadaSA->setUser($SantiagoA);
        $manager->persist($portadaSA);
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