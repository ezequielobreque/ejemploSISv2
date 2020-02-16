<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->getUser()==null){

            return $this->render('default/index.html.twig');


        }else{


        return $this->redirectToRoute('miMuro');
    }/*
            $form = $this->createFormBuilder()
                ->setMethod('GET')
                ->add('nombre', TextType::class)
                ->add('filtrar', SubmitType::class, ['label' => 'Buscar'])
                ->getForm();

            $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
            $qb->select('p')
                ->from('AppBundle:User', 'p');

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $criteria = $form->getData();

                $qb->where( $qb->expr()->like('p.username', '?1'))
                    ->setParameter(1, '%' . $criteria['nombre'] . '%');
            }

            $busqueda = $qb->getQuery()->getResult();
            $busqueda=$busqueda[0];


            return $this->render(
                'default/index.html.twig',['base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'busqueda' => $busqueda, 'form' => $form->createView()]);


*/

    }





    /*public function myMuro(){
        $em= $this->getDoctrine()->getManager();
        $usuario = $em->getRepository(\AppBundle\Entity\User::class)->findOneBy(['username'=>'Ezequiel']);
        $seguidos=$usuario->getLosQueSigo();
        $nuevo= $usuario->getMensajes();
        foreach ($seguidos as $s) {

            $nuevo[]=$s.getMensaje(1);

        }
        function cmp_obj($a, $b)
        {
            $al = strtolower($a->getFechaHora());
            $bl = strtolower($b->getFechaHora());
            if ($al == $bl) {
                return 0;
            }
            return ($al > $bl) ? +1 : -1;
        }
        usort($nuevo,array(Mensaje,"cmp_obj"));





        return $this ->render('2base.html.twig',['mensajes'=>$nuevo]);



    }*/




    /**
     * @Route("/lucky/number", name="luckynumber")
     */
    public function luckyNumberAction(Request $request)
    {
        /*$em= $this->getDoctrine()->getManager();*/

        $number = random_int(0, 100);

        return $this ->render('luckynumber.html.twig',['luckynumber'=>$number]);

        /*$usuario= $this->getDoctrine()
                        ->getRepository(\AppBundle\Entity\User::class)
                        ->find(1);

        $usuario->addMensaje(new Mensaje("mi primer mensaje"))
                ->addMensaje(new Mensaje("mi primer mensaje"))
            ->addMensaje(new Mensaje("mi primer mensaje"));

        $em->persist($usuario);
        $em->flush();*/
    }
}
