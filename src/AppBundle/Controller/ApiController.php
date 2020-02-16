<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 24/6/2019
 * Time: 7:28 PM
 */

namespace AppBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Route;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Response;


class ApiController extends FOSRestController
{
    /**
     * @Route("/api")
     */
    public function indexAction()
    {
        $data = array("hello" => "world");
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/mensajes", name="devolverMensajes")

     *
     */
    public function devolverAction()
    {

        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        if (!$auth) {
            throw new AccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();*/


        $entityManager = $this->getDoctrine()->getManager();


        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
        ->orderBy('m.fechaHora', 'DESC');

        $query = $qb->getQuery();

        $result =$query->getResult();

        $view = $this->view($result);

        $view->getContext()->setGroups(['default','list']);

        // $view->setSerializerGruops(array('list'));

        return $this->handleView($view);


    }

    /**
     * @Route("/api/{user}/mensajes", name="MensajesUser")

     *
     */
    public function MensajeUserAction(String $user)
    {




        $entityManager = $this->getDoctrine()->getManager();


        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.username = :user')
            ->orderBy('m.fechaHora', 'DESC')
            ->setParameter('user', $user);

        $query = $qb->getQuery();

        $result =$query->getResult();


        $view = $this->view($result);

        $view->getContext()->setGroups(['default','list']);

        // $view->setSerializerGruops(array('list'));

        return $this->handleView($view);

    }

    /**
     * @Route("/api/busquedas/usuarios", name="busquedas_api_usuarios")
     */
    public function buscarUsuariosAction(Request $request)
    {
        $resultados = null;
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
        $qb->select('p')
            ->from('AppBundle:User', 'p');

        $busqueda = $request->get('busqueda');

        if ($busqueda) {
            $qb->where($qb->expr()->like('p.username', '?1'))
                ->setParameter(1, '%' . $busqueda . '%');
            $resultados = $qb->getQuery()->getResult();


        }

        $view = $this->view($resultados);

        $view->getContext()->setGroups(['default','list']);

        // $view->setSerializerGruops(array('list'));

        return $this->handleView($view);
    }


}