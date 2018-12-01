<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
  /**
     * @Route("contact-us", name="home_contact")
     *      
     */
    public function index(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) { 
 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
             $this->addFlash('info', "Message envoyé avec succès");
             return $this->redirectToRoute('home_contact');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
