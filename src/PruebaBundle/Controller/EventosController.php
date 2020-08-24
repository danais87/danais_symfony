<?php

namespace PruebaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PruebaBundle\Entity\Eventos;
use PruebaBundle\Entity\Categorias;
use Symfony\Component\Validator\Constraints\DateTime;
use PruebaBundle\Form\EventosType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EventosController extends Controller
{
    public function allAction()
    {
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Eventos');
        $events = $repository->findAll();
        return $this->render("@Prueba/Eventos/all.html.twig",array("eventos"=>$events));
    }

    public function createAction()
    {
        $creationDate= new \DateTime();  
        $evento = new Eventos();
        $evento->setNombreEvento('evento3');
        $evento->setFecha($creationDate);
        $evento->setCiudad('guayaquil');

        $repository = $this->getDoctrine()->getManager();      
        $repository->persist($evento);
        $repository->flush();

        return $this->render("@Prueba/Eventos/all.html.twig",array("eventos"=>$evento->getId()));
    }

    public function updateAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $evento = $entityManager->getRepository(Eventos::class)->find($id);

        if (!$evento) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $evento->setNombreEvento('New product name!');
        $entityManager->flush();

        return $this->render("@Prueba/Eventos/update.html.twig",array("eventos"=>$evento->getId()));
    }

    public function nuevoAction(Request $request)
    {
        $evento = new Eventos();
        $form = $this->createForm(EventosType::class,$evento);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $evento= $form->getData();
            $em = $this->getDoctrine()->getManager();      
            $em->persist($evento);
            $em->flush();
            return $this->rendirectToRoute('exitoEvento');
        }
        return $this->render("@Prueba/Eventos/nuevo.html.twig",array("form"=>$form->createView()));
    }

    public function nuevoConCatAction()
    {
        $categoria = new Categorias();
        $categoria->setNombre("Fiestas");

        $evento = new Eventos();
        $evento->setNombreEvento('10K evento');
        $evento->setFecha(new \DateTime());
        $evento->setCiudad('habana');
        $evento->setCategoria($categoria);

        $em = $this->getDoctrine()->getManager();      
        $em->persist($categoria);
        $em->persist($evento);
        $em->flush();

        return $this->redirectToRoute('all_eventos');
       
    }
collation-server     = utf8mb4_general_ci
character-set-server = utf8mb4
}
