<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 31/5/2019
 * Time: 2:36 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class UsuarioController extends controller
{

    /**
     * @Route("/perfil/{id}", name="userPerfil")
     */
    public function getPerfil(Request $request,string $id)
    {

        $entityManager = $this->getDoctrine()->getManager();


        $resultados=null;
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('AppBundle:Mensaje', 'p');

        $busqueda = $request->get('megusta');

        if ($busqueda) {
            $qb->where('p.id= :id')
                ->setParameter('id',$busqueda);
            $resultados = $qb->getQuery()->getResult();

            if(in_array($this->getUser(),$resultados[0]->getMegusta()->toArray())){

                $resultados[0]->sacarMeGusta($this->getUser());
                $entityManager->flush();
            }

            else{

                $resultados[0]->addMeGusta($this->getUser());
                $entityManager->flush();
            }


        }
        $entityManager = $this->getDoctrine()->getManager();


        $resultados = null;
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('AppBundle:User', 'p');

        $busqueda = $request->get('Seguir');

        if ($busqueda) {
            $qb->where('p.id= :id')
                ->setParameter('id', $busqueda);
            $resultados = $qb->getQuery()->getResult();

            if (in_array($this->getUser(), $resultados[0]->getMisSeguidores()->toArray())) {

                $this->getUser()->dejarSeguir($resultados[0]);
                $entityManager->flush();
            } else {

                $this->getUser()->addSeguir($resultados[0]);
                $entityManager->flush();
            }


        }



        $em= $this->getDoctrine()->getManager();
        $qb= $em->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.username = :username')
            ->orderBy('m.fechaHora', 'DESC')
            ->setParameter('username', $id);


        $qb->setFirstResult(0)
            ->setMaxResults(10);
        $query = $qb->getQuery();

        $mensajes=$query->getResult();

        $em= $this->getDoctrine()->getManager();
        $qb= $em->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:User', 'm')
            ->where('m.username = :username')
            ->setParameter('username', $id);



        $query = $qb->getQuery();

        $user=$query->getResult();
        $user=$user[0];

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


        return $this ->render('usuario/usuario.html.twig',['msg'=>$mensajes,'user'=>$user,'noamigos'=>$noami]);



    }



    public function seguirPerfil(Request $request)
    {

    }
}