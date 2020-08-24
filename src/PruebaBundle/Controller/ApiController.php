<?php

namespace PruebaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PruebaBundle\Entity\Eventos;
use PruebaBundle\Entity\Categorias;
use Symfony\Component\Validator\Constraints\DateTime;
use PruebaBundle\Form\EventosType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ApiController extends Controller
{
    const BAD_NAME_COMPANY = 'Falta nombre en la peticion';
    const BAD_NAME_COMPANY_HELP = 'EJEMPLO';

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
    public function badRequest($msg,$help=null)
    {
        return array(
            'mensage'=>$msg,
            'help'=>$help
        );
    }
    private function serializeEvento(Eventos $evento)
    {
      return array(
          'nombre' => $evento->getNombreEvento(),
          'ciudad' => $evento->getCiudad()
      );
    }
    public function eventoAction($nombre)
    {
        if($nombre== 'Sin definir'){
            $response = new JsonResponse($this->badRequest(self::BAD_NAME_COMPANY,self::BAD_NAME_COMPANY_HELP),400);
        } else {
            $repository = $this->getDoctrine()->getRepository('PruebaBundle:Eventos');
            $evento = $repository->findOneBynombreEvento($nombre);
            if(isset($evento)){
                $data['evento'][] = $this->serializeEvento($evento);
                $response = new JsonResponse($data,200);
            }else {
                $response = new JsonResponse($this->badRequest("error","errorrrrr"));
            }
        }      
        return $response;

    }

    public function crearEventoAction(Request $request)
    {
      //En primer lugar comprobaremos que todos los campos necesarios
      //para la inserción existen
      if(
        $request->request->get('nombre')==null
        ||
        $request->request->get('ciudad')==null     
        )
        {
          $response = new JsonResponse($this->badRequest(self::NO_ALL_ELEMENTS,""), 400);
        }else{
          //generamos la evento a partir de los datos
          $evento = new Eventos();
          $evento->setNombreEvento($request->request->get('nombre'));
          $evento->setCiudad($request->request->get('ciudad'));
          //Salvamos la evento
          $em = $this->getDoctrine()->getManager();
          $em->persist($evento);
          $em->flush();
          //Devolvemos la empresa en el JsonResponse
          $data['evento'][] = $this->serializeEvento($evento);
          $response = new JsonResponse($data, 200);
        }
        return $response;
    }

}
