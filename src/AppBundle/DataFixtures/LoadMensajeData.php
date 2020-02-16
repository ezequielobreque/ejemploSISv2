<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 10/5/2019
 * Time: 9:25 PM
 */

namespace AppBundle\DataFixtures;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Mensaje;

class LoadMensajeData Extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {


        /*$number = random_int(0, 100);*/

        /*return $this ->render('luckynumber.html.twig',['luckynumber'=>$number]);*/

        /*$usuario= $manager
            ->getRepository(\AppBundle\Entity\User::class)
            ->find(1);
        */
        $userAdmin = $this->getReference('EzequielO');
        $userAdmin->addMensaje(new Mensaje("mi primer mensaje"))
            ->addMensaje(new Mensaje("mi segundo mensaje"))
            ->addMensaje(new Mensaje("mi tercer mensaje"));

        $manager->persist($userAdmin);
        $FacuT1 = $this->getReference('FacuT');
        $FacuT1->addMensaje(new Mensaje("mi mensaje Facu 1"))
            ->addMensaje(new Mensaje("mi Mensaje facu 2"))
            ->addMensaje(new Mensaje("mi Mensaje facu 3"));

        $manager->persist($FacuT1);

        $AbrilM1 = $this->getReference('AbrilM');
        $AbrilM1->addMensaje(new Mensaje("mi mensaje Abril 1"))
            ->addMensaje(new Mensaje("mi Mensaje Abril 2"))
            ->addMensaje(new Mensaje("mi Mensaje Abril 3"));

        $manager->persist($AbrilM1);
        $SantiagoA = $this->getReference('SantiagoA');
        $SantiagoA->addMensaje(new Mensaje("mi mensaje Santiago 1"))
            ->addMensaje(new Mensaje("mi Mensaje Santiago 2"))
            ->addMensaje(new Mensaje("mi Mensaje Santiago 3"));

        $manager->persist($SantiagoA);
        $manager->flush();
    }
    public function getOrder()
    {
        return 2;  // TODO: Implement getOrder() method.
    }

}