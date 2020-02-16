<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 17/5/2019
 * Time: 1:24 PM
 */

namespace AppBundle\DataFixtures;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\MeGusta;

class LoadMeGustaData Extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {


        $FacuT = $this->getReference('FacuT');
        $AbrilM = $this->getReference('AbrilM');
        $SantiagoA = $this->getReference('SantiagoA');
        $EzequielO = $this->getReference('EzequielO');
        $mensaje1 = $EzequielO->getMensaje(0);
        $FacuT->DarMeGusta($mensaje1);
        $AbrilM->DarMeGusta($mensaje1);
        $SantiagoA->DarMeGusta($mensaje1);
        $manager->persist($EzequielO,$AbrilM,$FacuT,$SantiagoA);
        $manager->flush();
    }
    public function getOrder()
    {
        return 4;  // TODO: Implement getOrder() method.
    }

}