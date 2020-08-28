<?php

namespace ServiceBundle\Controller;

use ServiceBundle\Entity\Impression;
use ServiceBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Impression controller.
 *
 */
class ImpressionController extends Controller
{
    /**
     * Lists all impression entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $impressions = $em->getRepository('ServiceBundle:Impression')->findAll();

        return $this->render('impression/index.html.twig', array(
            'impressions' => $impressions,
        ));
    }

    /**
     * Creates a new impression entity.
     *
     */
    public function newAction(Request $request)
    {
        $impression = new Impression();
        $form = $this->createForm('ServiceBundle\Form\ImpressionType', $impression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $host= $_SERVER["HTTP_HOST"];
            $url= $_SERVER["REQUEST_URI"];
            $impression->setPage("http://" . $host . $url);  
            $ipaddress = '';
            if (array_key_exists('HTTP_CLIENT_IP',$_SERVER))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(array_key_exists('HTTP_X_FORWARDED',$_SERVER))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(array_key_exists('HTTP_FORWARDED_FOR',$_SERVER))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(array_key_exists('HTTP_FORWARDED',$_SERVER))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(array_key_exists('REMOTE_ADDR',$_SERVER))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            $impression->setIpAddress($ipaddress);
            $impression->setCreatedAt(new \DateTime());     
            $impression->setPhoto($form->get('photo')->getData()); 

            $em->persist($impression);
            $em->flush();

            return $this->redirectToRoute('impression_show', array('id' => $impression->getId()));
        }

        return $this->render('impression/new.html.twig', array(
            'impression' => $impression,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a impression entity.
     *
     */
    public function showAction(Impression $impression)
    {
        $deleteForm = $this->createDeleteForm($impression);

        return $this->render('impression/show.html.twig', array(
            'impression' => $impression,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing impression entity.
     *
     */
    public function editAction(Request $request, Impression $impression)
    {
        $deleteForm = $this->createDeleteForm($impression);
        $editForm = $this->createForm('ServiceBundle\Form\ImpressionType', $impression);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('impression_edit', array('id' => $impression->getId()));
        }

        return $this->render('impression/edit.html.twig', array(
            'impression' => $impression,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a impression entity.
     *
     */
    public function deleteAction(Request $request, Impression $impression)
    {
        $form = $this->createDeleteForm($impression);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($impression);
            $em->flush();
        }

        return $this->redirectToRoute('impression_index');
    }

    /**
     * Creates a form to delete a impression entity.
     *
     * @param Impression $impression The impression entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Impression $impression)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('impression_delete', array('id' => $impression->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

        /**
     * Creates a impression_newapi impression entity.
     *
     */
    public function impression_newapiAction($nombre)
    {
        $impression = new Impression();
       

        $repositoryP = $this->getDoctrine()->getRepository('ServiceBundle:Photo');
        $photo = $repositoryP->findOneBy(['url' => $nombre]);
        
        if(isset($photo)){
            $em = $this->getDoctrine()->getManager();

            $host= $_SERVER["HTTP_HOST"];
            $url= $_SERVER["REQUEST_URI"];

            $impression->setPage("http://" . $host . $url);  

            $ipaddress = '';
            if (array_key_exists('HTTP_CLIENT_IP',$_SERVER))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(array_key_exists('HTTP_X_FORWARDED_FOR',$_SERVER))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(array_key_exists('HTTP_X_FORWARDED',$_SERVER))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(array_key_exists('HTTP_FORWARDED_FOR',$_SERVER))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(array_key_exists('HTTP_FORWARDED',$_SERVER))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(array_key_exists('REMOTE_ADDR',$_SERVER))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            $impression->setIpAddress($ipaddress);
            $impression->setCreatedAt(new \DateTime());     
            $impression->setPhoto($photo); 
         
            $photo->setTotalImpressions($photo->GetTotalImpressions()+1);
            $em->persist($impression);
            $em->persist($photo);
            $em->flush();

            $response = new JsonResponse("exito",200);
        }
        else {
            $response = new JsonResponse("error",400); 
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
