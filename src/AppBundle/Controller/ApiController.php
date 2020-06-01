<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 24/6/2019
 * Time: 7:28 PM
 */

namespace AppBundle\Controller;
use AppBundle\AppBundle;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\IntegerType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\Route;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use AppBundle\Entity\Portada;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\VichUploaderBundle;


class ApiController extends FOSRestController
{






    /**
 * @Route("/api")
 */
    public function indexAction(Request $request)
    {
        /*  $manager= $this->getDoctrine()->getManager();

          $nuevoUsuario=new User();
          $nuevoUsuario ->setUsername('x');
          $nuevoUsuario->setPlainPassword('o');
          $nuevoUsuario->setEmail('camioner@gmail.com');
          $nuevoUsuario ->setEnabled(1);
          $manager->persist($nuevoUsuario);
          $manager->flush();*/


        $data = array('mensaje'=>$request->get('username'));
        $view = $this->view($data);

        return $this->handleView($view);


        /*  $data = array("hello" => "world");
          $view = $this->view($data);
          return $this->handleView($view);*/
    }


    /**
     * @Route("/api/esta")
     */
   public function estaAction(Request $request)
    {
        /*  $manager= $this->getDoctrine()->getManager();

          $nuevoUsuario=new User();
          $nuevoUsuario ->setUsername('x');
          $nuevoUsuario->setPlainPassword('o');
          $nuevoUsuario->setEmail('camioner@gmail.com');
          $nuevoUsuario ->setEnabled(1);
          $manager->persist($nuevoUsuario);
          $manager->flush();*/
        $manager= $this->getDoctrine()->getManager();
        $nuevoUsuario=$this->getUser();
        $nuevaPortada=new Portada();
        $nuevoUsuario->setPortada($nuevaPortada);
        $nuevaPortada->setUser($nuevoUsuario);
        $manager->persist($nuevoUsuario,$nuevaPortada);
        $manager->flush();
        $data = array('mensaje'=>'terminado');
        $view = $this->view($data);

        return $this->handleView($view);


        /*  $data = array("hello" => "world");
          $view = $this->view($data);
          return $this->handleView($view);*/
    }

    /**
     * @Route("/api/sec/usuario" , name="usuarioapp")
     */
    public function usuarioAppAction()
    {
        $usuario=$this->getUser();
        $portada=$usuario->getPortada();
        $view = $this->view(array('user'=>$usuario,'portada'=>$portada));
        return $this->handleView($view);


        /*  $data = array("hello" => "world");
          $view = $this->view($data);
          return $this->handleView($view);*/
    }





    /**
     * @Route("/api/signup" , name="signup")
     */
    public function signupAction(Request $request)
    {


        $manager= $this->getDoctrine()->getManager();


        $nuevoUsuario=new User();
        $nuevaPortada=new Portada();
        $nuevoUsuario ->setUsername($request->get('username'));
        $nuevoUsuario->setPlainPassword($request->get('password'));
        $nuevoUsuario->setEmail($request->get('email'));
        $nuevoUsuario ->setEnabled(1);
        $nuevoUsuario->setPortada($nuevaPortada);
        $nuevaPortada->setUser($nuevoUsuario);
        $manager->persist($nuevoUsuario);
        $manager->persist($nuevaPortada);
        $manager->flush();


        $data = array('mensaje'=>$nuevoUsuario);
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * @Route("/api/sec/mimuro", name="mimuro")
    */
    public function mimuroAction(Request $request)
    {

        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        if (!$auth) {
            throw new AccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();*/

        $paginator  = $this->get('knp_paginator');
        $token=$this->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();



        $followsId = $entityManager->createQueryBuilder()
            ->select('seg.id')
            ->from('AppBundle:User', 'u')
            ->join('u.losQueSigo', 'seg')
            ->where('u.id = :id')
            ->setParameter("id", $token)
            ->getQuery()
            ->execute();

        $qb = $entityManager->createQueryBuilder()
            ->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.id IN(:ids)')
            ->orderBy('m.fechaHora','DESC')
            ->setParameter('ids', $followsId);

        $query = $qb->getQuery(); //->execute();

        $paginado = $paginator->paginate(
            $query,
            $request->get('page',1),
            $request->get('limit',10)
        );
        $view = $this->view($paginado);


       // $view->getContext()->setGroups(['default','list']);
        return $this->handleView($view);

        // $view->setSerializerGruops(array('list'));


    }

    /**
     * @Route("/api/sec/amigos", name="amigos")
     */
    public function misAmgiosAction(Request $request)
    {

        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        if (!$auth) {
            throw new AccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();*/

        $paginator  = $this->get('knp_paginator');
        $token=$this->getUser()->getId();

        $entityManager = $this->getDoctrine()->getManager();



        $followsId = $entityManager->createQueryBuilder()
            ->select('seg.id')
            ->from('AppBundle:User', 'u')
            ->join('u.losQueSigo', 'seg')
            ->where('u.id = :id')
            ->setParameter("id", $token)
            ->getQuery()
            ->execute();

        $query = $entityManager->createQueryBuilder()
            ->select('seg.id')
            ->from('AppBundle:User', 'u')
            ->join('u.losQueSigo', 'seg')
            ->where('u.id IN(:ids)')
            ->setParameter('ids', $followsId)
            ->groupBy('seg.id')
            ->getQuery()
            ->execute();

        $qb = $entityManager->createQueryBuilder();

         $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where($qb->expr()->andX(
                $qb->expr()->in('u.id',':ids'),
                $qb->expr()->notIn('u.id', ':mi')
            ))
            ->setParameters(array('ids' => $query, 'mi' => $followsId))
            ->getQuery();



        $paginado = $paginator->paginate(
            $qb,
            $request->get('page',1),
            $request->get('limit',10)
        );
        $view = $this->view($paginado);


        // $view->getContext()->setGroups(['default','list']);
        return $this->handleView($view);

        // $view->setSerializerGruops(array('list'));


    }

    /**
     * @Route("/api/sec/mismensajes", name="mismensajes")
     */
    public function mismensajesAction(Request $request)
    {

        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        if (!$auth) {
            throw new AccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();*/

        $paginator  = $this->get('knp_paginator');
        $entityManager = $this->getDoctrine()->getManager();


        $qb = $entityManager->createQueryBuilder()
            ->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.id = :ids')
            ->orderBy('m.fechaHora', 'DESC')
            ->setParameter('ids', $this->getUser()->getId());

        $query = $qb->getQuery(); //->execute();

        $paginado = $paginator->paginate(
            $query,
            $request->get('page',1),
            $request->get('limit',10)
        );
        $view = $this->view($paginado);

        return $this->handleView($view);

        // $view->setSerializerGruops(array('list'));


    }


    /**
     * @Route("/api/sec/editarmensaje/{id}", name="editarmensaje")
     */
    public function editarMensajeAction(String $id,Request $request)
    {

        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
        if (!$auth) {
            throw new AccessDeniedException();
        }

        $user = $this->get('security.token_storage')->getToken()->getUser();*/


        $user=$this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->where('m.id = :ids')
            ->setParameter('ids', $id);



        $query = $qb->getQuery();
        $mensa=$query->getResult();

        $lista= $user->getMensajes();
        $actualizado='mensaje no encontrado';
        if($mensa!=null){
            foreach($lista as $value){
                if($value->getid()==$mensa[0]->getId()){
                    $value->setInformacion($request->get('informacion'));

                   if($request->files->get('imagefile')!=null){

                        $file = $request->files->get('imagefile');

                        $value->setImageFile($file);
                    }else if($request->get('imagename')=='null') {
                        $value->setImageName(null);
                    }

                    $entityManager->persist($value);
                    $entityManager->flush();
                    $actualizado='mensaje actualizado';
                }
            }}



        $data = array('mensaje'=>$actualizado);
        $view = $this->view($data);
        return $this->handleView($view);

        // $view->setSerializerGruops(array('list'));


    }




    /**
     * @Route("/api/sec/crearmensaje", name="crearmensaje")
     */
    public function crearMensajeAction(Request $request)
    {
        $user=$this->getUser();

        $entityManager = $this->getDoctrine()->getManager();



        //$this->createForm(new PostType(), $entity, array("method" => $request->getMethod()))->add('imageFile','file');


        $nuevoMensaje=new Mensaje($request->get('informacion'));

        $file = $request->files->get('imagefile');

        $nuevoMensaje->setImageFile($file);
        $nuevoMensaje->setUser($user);

        $user->addMensaje($nuevoMensaje);
        $entityManager->persist($nuevoMensaje , $user);
        $entityManager->flush();


        $data = array('mensaje'=>$nuevoMensaje);
        $view = $this->view($data);
        return $this->handleView($view);
        // $view->setSerializerGruops(array('list'));


    }

    /**
     * @Route("/api/sec/editarperfil", name="editarPerfil")
     */
    public function editarPefilAction(Request $request)
    {
        /*$auth = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
         if (!$auth) {
             throw new AccessDeniedException();
         }

         $user = $this->get('security.token_storage')->getToken()->getUser();*/
        $entityManager = $this->getDoctrine()->getManager();

        $user=$this->getUser();

        $portada=$user->getPortada();
           if($request->get('username')!=null);
        {
            $user->setUsername($request->get('username'));
        }

        if($request->get('email')!=null);
        {
            $user->setEmail($request->get('email'));
        }
        if($request->files->get('imageportada')!=null){

            $filep = $request->files->get('imageportada');

            $portada->setImageFile($filep);
        }else if($request->get('imagenameportada')=='null') {
            $portada->setImageName(null);
        }

       if($request->files->get('imageuser')!=null){

            $fileu = $request->files->get('imageuser');

            $user->setImageFile($fileu);
        }else if($request->get('imagenameuser')=='null') {
            $user->setImageName(null);
        }

            $entityManager->persist($portada);
            $entityManager->persist($user);
             $entityManager->flush();
             $actualizado='perfil actualizado';



        /*
                if($value->getid()==$mensa[0]->getId()){
                    $value->setInformacion($request->get('informacion'));

                    if($request->files->get('imagefile')!=null){

                        $file = $request->files->get('imagefile');

                        $value->setImageFile($file);
                    }else if($request->get('imagename')=='null') {
                        $value->setImageName(null);
                    }

                    $entityManager->persist($value);
                    $entityManager->flush();
                    $actualizado='mensaje actualizado';
                }
            }}*/



        $data = array('perfil'=>$actualizado);
        $view = $this->view($data);
        return $this->handleView($view);


    }


    /**
     * @Route("/api/sec/eliminarMensaje/{id}", name="editarmensaje2")
     */
    public function eliminarMensajeAction(String $id,Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->where('m.id = :ids')
            ->setParameter('ids', $id);



        $query = $qb->getQuery();
        $mensa=$query->getResult();
        $mensaje= $mensa[0];



            $entityManager->remove($mensaje);
            $entityManager->flush();



        $data = array('mensaje'=>'Dato borrado');
        $view = $this->view($data);
        return $this->handleView($view);


    }
    /**
     * @Route("/api/sec/seguiramigo/{id}", name="seguirAmigo")
     *
     */
    public function seguirAmigo(Request $request,String $id){
    $entityManager = $this->getDoctrine()->getManager();

        $qb= $entityManager->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.id = :ids')
            ->setParameter('ids', $id);

        $var=true;

        $qb = $qb->getQuery()->getResult();
        if (in_array($qb[0], $this->getUser()->getLosQueSigo()->toArray())) {
        $var=false;
        $this->getUser()->dejarSeguir($qb[0]);
        $entityManager->flush();
        } else {
            $var=true;
            $this->getUser()->addSeguir($qb[0]);
            $entityManager->flush();
        }
        $data = array('loSigo'=>$var);
        $view = $this->view($data);
        return $this->handleView($view);

        }

    /**
     * @Route("/api/sec/seguidos", name="Seguidos")
     *
     */
    public function Seguidos(Request $request){

        $var=$this->getUser()->getLosQueSigo();

        $view = $this->view($var);
        return $this->handleView($view);

    }


    /**
     * @Route("/api/sec/mensajes", name="devolverMensajes")

     *
     */
    public function devolverAction(Request $request)
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
     * @Route("/api/sec/{user}/mensajes", name="MensajesUser")

     *
     */
    public function MensajeUserAction(Request $request,String $user)
    {




        $paginator  = $this->get('knp_paginator');

        $entityManager = $this->getDoctrine()->getManager();

        int:$identificador=$user;

        $qb= $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Mensaje', 'm')
            ->join('m.user', 'u')
            ->where('u.id = :id')
            ->orderBy('m.fechaHora' , 'DESC')
            ->setParameter('id', $identificador);

        $query = $qb->getQuery();

        $paginado = $paginator->paginate(
            $query,
            $request->get('page',1),
            $request->get('limit',10)
        );
        $view = $this->view($paginado);

        return $this->handleView($view);


        /*$view = $this->view($result);


        $view->getContext()->setGroups(['default','list']);

        // $view->setSerializerGruops(array('list'));

        return $this->handleView($view);*/

    }




    /**
     * @Route("/api/sec/busquedas/usuarios", name="busquedas_api_usuarios")
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

        return $this->handleView($view);;
    }


    /**
     * @Route("/api/sec/megusta/{id}", name="megustamensaje")
     */
    public function MegustaAction(String $id)
    {
         $user=$this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
        $qb->select('m')

            ->from('AppBundle:Mensaje', 'm');
        Int: $busqueda =  $id;

        if ($busqueda) {
            $qb->where('m.id= :id')
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







        $view = $this->view($resultados);

        $view->getContext()->setGroups(['default','list']);

        // $view->setSerializerGruops(array('list'));

        return $this->handleView($view);;
    }

    /**/



}