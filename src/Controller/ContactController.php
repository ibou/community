<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use App\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ContactController extends AbstractController
{
    /**
     * @Route("contact-us", name="home_contact")
     */
    public function index(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $args = [
                'path' => $this->getParameter('app.hostname'),
            ];

            //On déclenche l'event
            $event = new GenericEvent($contact);
            $event->setArguments($args);
            $eventDispatcher->dispatch(Events::USER_CONTACT, $event);

            $this->addFlash('info', 'Message envoyé avec succès');

            return $this->redirectToRoute('home_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
