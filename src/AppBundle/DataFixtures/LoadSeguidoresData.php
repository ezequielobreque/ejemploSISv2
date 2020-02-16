<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 17/5/2019
 * Time: 1:25 PM
 */

namespace AppBundle\DataFixtures;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\User;

class LoadSeguidoresData Extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
       /* $userAdmin =new User();
        $userAdmin ->setUsername('admin');
        $userAdmin ->setPassword('test');
        $userAdmin ->setEmail('ezequiel@gmail.com');
        $manager->persist($userAdmin);




        $this->addReference('admin-user',$userAdmin);
        $manager->persist($userAdmin);
        $manager->flush();*/
        $FacuT = $this->getReference('FacuT');
        $AbrilM = $this->getReference('AbrilM');
        $SantiagoA = $this->getReference('SantiagoA');
        $EzequielO = $this->getReference('EzequielO');
        $EzequielO->addSeguir($SantiagoA)
                ->addSeguir($AbrilM)
                ->addSeguir($FacuT);
        $FacuT->addSeguir($EzequielO)
               ->addSeguir($AbrilM);
        $SantiagoA->addSeguir($FacuT);



        $manager->persist($EzequielO,$AbrilM,$FacuT,$SantiagoA);
        $manager->flush();
    }
    public function getOrder()
    {
        return 3;  // TODO: Implement getOrder() method.
    }
}