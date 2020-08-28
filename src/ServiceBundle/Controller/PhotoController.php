<?php

namespace ServiceBundle\Controller;

use ServiceBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\File;
/**
 * Photo controller.
 *
 */
class PhotoController extends Controller
{
    
    const BAD_NAME_COMPANY = 'Falta nombre en la peticion';
    const BAD_NAME_COMPANY_HELP = 'EJEMPLO';

    /**
     * Lists all photo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $photos = $em->getRepository('ServiceBundle:Photo')->findAll();
       
        return $this->render('photo/index.html.twig', array(
            'photos' => $photos,
        ));
    }

    /**
     * Creates a new photo entity.
     *
     */
    public function newAction(Request $request)
    {
        $photo = new Photo();
        $form = $this->createForm('ServiceBundle\Form\PhotoType', $photo);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $file= $form["url"]->getData();
            $ext= $file->guessExtension();
            $file_name = time().".".$ext;
            $file->move("uploads",$file_name);

            $photo->setTotalImpressions($form->get('totalImpressions')->getData());
            $photo->setCreatedAt(new \DateTime());
            $photo->setUrl($file_name);            
            $em->persist($photo);
            $em->flush();

            return $this->redirectToRoute('photo_show', array('id' => $photo->getId()));
        }

        return $this->render('photo/new.html.twig', array(
            'photo' => $photo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a photo entity.
     *
     */
    public function showAction(Photo $photo)
    {
        $deleteForm = $this->createDeleteForm($photo);

        return $this->render('photo/show.html.twig', array(
            'photo' => $photo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing photo entity.
     *
     */
    public function editAction(Request $request, Photo $photo)
    {
        $deleteForm = $this->createDeleteForm($photo);
        $editForm = $this->createForm('ServiceBundle\Form\PhotoType', $photo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('photo_edit', array('id' => $photo->getId()));
        }

        return $this->render('photo/edit.html.twig', array(
            'photo' => $photo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a photo entity.
     *
     */
    public function deleteAction(Request $request, Photo $photo)
    {
        $form = $this->createDeleteForm($photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($photo);
            $em->flush();
        }

        return $this->redirectToRoute('photo_index');
    }

    /**
     * Creates a form to delete a photo entity.
     *
     * @param Photo $photo The photo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Photo $photo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('photo_delete', array('id' => $photo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function badRequest($msg,$help=null)
    {
        return array(
            'mensage'=>$msg,
            'help'=>$help
        );
    }

    private function serializePhotos($evento)
    { 
        $host= $_SERVER["HTTP_HOST"];
   
        foreach($evento as $event)
        {
            $output[] = array('imp'=>$event->getTotalImpressions(), 'url'=>"http://".$host."/uploads/".$event->getUrl());
        }
      return $output ;

    }

    private function serializePhoto(Photo $e)
    {
      return array(
          'imp' => $e->getTotalImpressions(),
          'url' => $e->getUrl()
      );
    }

    public function apiPhotoAction()
    {
            $repository = $this->getDoctrine()->getRepository('ServiceBundle:Photo');
            $evento = $repository->findAll();
            if(isset($evento)){
                $data['photo'][] = $this->serializePhotos($evento);
                $response = new JsonResponse($data,200);
            }else {
                $response = new JsonResponse($this->badRequest(self::BAD_NAME_COMPANY,self::BAD_NAME_COMPANY_HELP));
            }
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;

    }
    public function getPhotoAction($nombre)
    {
        $file='';
        
        if($nombre== 'Sin definir'){
            $response = new JsonResponse($this->badRequest(self::BAD_NAME_COMPANY,self::BAD_NAME_COMPANY_HELP),400);
        } else {
            $repository = $this->getDoctrine()->getRepository('ServiceBundle:Photo');
            $photo = $repository->findOneBy(['url' => $nombre]);
            if(isset($photo)){
              
                $file = new File('/web/uploads/'.$photo);
   
            }else {
                $response = new JsonResponse($this->badRequest(self::BAD_NAME_COMPANY,self::BAD_NAME_COMPANY_HELP));
            }
        }            
        return $this->redirectToRoute('photo_index');
       // return $this->file($file);
    }

    public function pruebaAction($nombre)
    {
        $repository = $this->getDoctrine()->getRepository('ServiceBundle:Photo');
        $photo = $repository->findOneBy(['url' => $nombre]);
        return $this->render('photo/prueba.html.twig',array("url"=>$photo));
       // return $this->file($file);
    }

}
