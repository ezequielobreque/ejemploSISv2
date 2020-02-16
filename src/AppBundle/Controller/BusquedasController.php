<?php
/**
 * Created by PhpStorm.
 * User: ezequiel_o
 * Date: 10/6/2019
 * Time: 11:10 AM
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BusquedasController extends Controller
{

    /**
     * @Route("/busquedas/usuarios", name="busquedas_usuarios")
     */
    public function buscarUsuariosAction(Request $request)
    {
        $resultados=null;
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder('p');
        $qb->select('p')

            ->from('AppBundle:User', 'p');

        $busqueda = $request->get('busqueda');

        if ($busqueda) {
            $qb->where( $qb->expr()->like('p.username', '?1'))
                ->setParameter(1, '%' . $busqueda . '%');
            $resultados = $qb->getQuery()->getResult();


        }







        return $this->render(
            'busquedas/resultados.html.twig',[
                'resultados' => $resultados,'busqueda'=>$busqueda
            ]);

    }

}