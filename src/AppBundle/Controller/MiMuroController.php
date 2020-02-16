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

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MiMuroController extends controller
{




    /**
     * @Route("/mimuro", name="miMuro")
     */
    public function getMuroAction(Request $request)
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






        // $em= $this->getDoctrine()->getManager();
        //$usuario = $em->createQuery("SELECT u FROM AppBundle\Entity\User u  WHERE u.seguidos ='Ezequiel'");

        $user=$this->getUser();
        $mensaje= new Mensaje("");
        $mensaje->setUser($user);


        $form = $this->createFormBuilder($mensaje)
            ->add('informacion', TextType::class,['label'=>$user->getUsername(),'attr'=>['placeholder'=>'en que estas pensando?',]])
             ->add('imageFile', VichImageType::class,['required' => false,
                 'allow_delete' => true,
                    ])
            ->add('save', SubmitType::class, ['label' => 'postear','attr'=>['type'=>'button','class'=>'btn btn-outline-primary btn-sm float-right']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->addMensaje($mensaje);
            $entityManager->persist($user);

            $entityManager->flush();

            return $this->redirectToRoute('miMuro');
        }



        $expr = $entityManager->getExpressionBuilder();

        $followsId = $entityManager->createQueryBuilder()
            ->select('seg.id')
            ->from('AppBundle:User', 'u')
            ->join('u.losQueSigo', 'seg')
            ->where('u.id = :id')
            ->getQuery()
            ->setParameter("id", $this->getUser()->getId())
            ->execute();

        $qb = $entityManager->createQueryBuilder()
            ->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.id IN(:ids)')
            ->orderBy('m.fechaHora','DESC')
            ->setParameter('ids', $followsId);
        $qb->setFirstResult(0)
            ->setMaxResults(5);
        $query = $qb->getQuery(); //->execute();
        $user =$query->getResult();


        //$usuario= $usuario->getResult();
        //   if($usuario !== null){
        //       $user=$usuario[0]    ;

        // }

        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:User', 'm');



        $qb->setFirstResult(0)
            ->setMaxResults(10);


        $query = $qb->getQuery();
        $usuarios=$query->getResult();
        $noamigos=array_diff($usuarios,array($this->getUser()),$this->getUser()->getLosQueSigo()->toArray());


        $noami=array_slice($noamigos, 0, 3);


        return $this ->render('perfil/mimuro.html.twig',['perfil'=>$user,'form' => $form->createView(),'noamigos'=>$noami]);



    }

    /**
     * @Route("/mensaje/{id}/edit", name="editar_mensaje")
     *
     */
    public function editMuroAction(Request $request,int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();




        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->where('m.id = :ids')
            ->setParameter('ids', $id);



        $query = $qb->getQuery();
        $mensa=$query->getResult();
        $mensaje=$mensa[0];
        $edit = $this->createFormBuilder($mensaje)
            ->add('informacion', TextType::class)
            ->add('imageFile', VichImageType::class,['required' => false,
                'allow_delete' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'editar mensaje'])
            ->getForm();

        $edit->handleRequest($request);

        if ($edit->isSubmitted() && $edit->isValid()) {



            $entityManager->flush();

            return $this->redirectToRoute('miPerfil');
        }
        return $this->render('perfil/mensaje_edit.html.twig', ['mensaje'=>$mensaje,
            'edit' => $edit->createView()
        ]);

    }

    /**
     * @Route("/mensaje/{id}/elim", name="eliminar_mensaje")
     *
     */
    public function eliminarMensajeAction(Request $request,int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();




        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->where('m.id = :ids')
            ->setParameter('ids', $id);



        $query = $qb->getQuery();
        $mensa=$query->getResult();
        $mensaje=$mensa[0];
        $edit = $this->createFormBuilder($mensaje)
            ->add('save', SubmitType::class, ['label' => 'Eliminar Mensaje'])
            ->getForm();

        $edit->handleRequest($request);

        if ($edit->isSubmitted() && $edit->isValid()) {


            $entityManager->remove($mensaje);
            $entityManager->flush();

            return $this->redirectToRoute('miPerfil');
        }
        return $this->render('perfil/mensaje_borrar.html.twig', ['mensaje'=>$mensaje,
            'edit' => $edit->createView()
        ]);

    }


}