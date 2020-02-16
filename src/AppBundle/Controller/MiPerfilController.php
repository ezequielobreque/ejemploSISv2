<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 31/5/2019
 * Time: 5:56 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MiPerfilController extends controller
{

    /**
     * @Route("/miperfil", name="miPerfil")
     */
    public function getMiPerfil()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $expr = $entityManager->getExpressionBuilder();

        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.id = :ids')
            ->orderBy('m.fechaHora', 'DESC')
            ->setParameter('ids', $this->getUser()->getId());



        $qb->setFirstResult(0)
            ->setMaxResults(10);


        $query = $qb->getQuery();
        $mensajes=$query->getResult();

        // $usuario = $em->getRepository(\AppBundle\Entity\User::class)->findOneBy( ['username'=>$id]);
        // $mensaje=$usuario->getMensajes();


        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:User', 'm');



        $qb->setFirstResult(0)
            ->setMaxResults(10);


        $query = $qb->getQuery();
        $usuarios=$query->getResult();
        $noamigos=array_diff($usuarios,array($this->getUser()),$this->getUser()->getLosQueSigo()->toArray());
        $noami=array_slice($noamigos, 0, 3);
        return $this ->render('perfil/miperfil.html.twig',['perfil'=>$mensajes,'noamigos'=>$noami]);



    }
    /**
     * @Route("/{nombre}/editar", name="editar_perfil")
     */
    public function editarMiPerfil(Request $request,String $nombre){

        $entityManager = $this->getDoctrine()->getManager();






        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:User', 'm')
            ->where('m.username = :username')
            ->setParameter('username', $nombre);



        $query = $qb->getQuery();
        $user=$query->getResult();
        $user=$user[0];

        $edit = $this->createFormBuilder($user)

            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('imageFile', VichImageType::class,['required' => false,
                'allow_delete' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'editar perfil'])
            ->getForm();

        $edit->handleRequest($request);

        if ($edit->isSubmitted() && $edit->isValid()) {



            $entityManager->flush();

            return $this->redirectToRoute('miPerfil');
        }
        return $this->render('perfil/perfil_editar.html.twig', ['user'=>$user,
            'edit' => $edit->createView()
        ]);


    }

}