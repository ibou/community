<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/client", name="client")
     */
    public function indexClient()
    {

        return $this->render(
            'home/index_client.html.twig',
            [
            'controller_name' => 'HomeController V2',
            ]
        );
    }

    /**
     * @Route("/client/films", name="client.films")
     */
    public function indexClientFilms()
    {

        return $this->render(
            'home/index_client_films.html.twig',
            [
            'controller_name' => 'HomeController V3',
            ]
        );
    }
    /**
     * @Route("/data", name="client.data")
     */
    public function data()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'author' => 'Chris Colborne',
                'avatarUrl' => 'http://1.gravatar.com/avatar/13dbc56733c2cc66fbc698cdb07fec12',
                'title' => 'Bitter Predation',
                'description' => 'Thirteen thin, round towers …',
            ],
            [
                'id' => 2,
                'author' => 'Louanne Perez',
                'avatarUrl' => 'https://randomuser.me/api/portraits/thumb/women/18.jpg',
                'title' => 'Strangers of the Ambitious',
                'description' => "A huge gate with thick metal doors …",
            ],
            [
                'id' => 3,
                'author' => 'Theodorus Dietvorst',
                'avatarUrl' => 'https://randomuser.me/api/portraits/thumb/men/49.jpg',
                'title' => 'Outsiders of the Mysterious',
                'description' => "Plain fields of a type of grass cover …",
            ],
        ]);
    }
}
