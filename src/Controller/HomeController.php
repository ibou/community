<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
 
    public function index()
    {
         
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("contact-us", name="home_contact")
     *      
     */
    public function contact(){
        return $this->render('contact/contact.html.twig');
    }
}
