<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 24/6/2019
 * Time: 2:59 PM
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class contraseniaController extends controller
{

    /**
     * @Route("/cambiar_contrasenia", name="CambiarC")
     */
    public function cambiarContraseniaAction(Request $request)
    {

        $user = $this->getUser();
        $newPasswordPlain = 'test';
        $currentPasswordForValidation = 'test2';

        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);
        $encodedPassword = $encoder->isPasswordValid($user->getPassword(),$currentPasswordForValidation,$user->getSalt());

        if ( $user->getPassword() == $encodedPassword ) {
            $userManager = $this->container->get('fos_user.user_manager');
            $user->setPlainPassword($newPasswordPlain);
            $userManager->updateUser($user, true);
            var_dump('perfecto actualizado');exit;
        } else {
            var_dump('error: not the same password');exit;
        }

        return $this->render('contraseña/cambiar_contraseña.html.twig', []);

    }
}
