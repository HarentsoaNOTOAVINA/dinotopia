<?php

namespace App\Controller;

use App\Entity\Dinosaur;
use App\Service\GitHubService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MainController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/', name: 'main_controller', methods: ['GET'])]
    public function index(GitHubService $github): Response
    {
        /** @var Dinosaur $dinos */
        $dinos = [
            (new Dinosaur())
                ->setName('Daisy')
                ->setGenus('Velociraptor')
                ->setLength(2)
                ->setEnclosure('Paddock A')
            ,
            (new Dinosaur())
                ->setName('Maverick')
                ->setGenus('Pterodactyl')
                ->setLength(7)
                ->setEnclosure('Aviary 1'),

            (new Dinosaur())
                ->setName('Big Eaty')
                ->setGenus('Tyrannosaurus')
                ->setLength(15)
                ->setEnclosure('Paddock C'),

            (new Dinosaur())
                ->setName('Dennis')
                ->setGenus('Dilophosaurus')
                ->setLength(6)
                ->setEnclosure('Paddock B'),

            (new Dinosaur())
                ->setName('Bumpy')
                ->setGenus('Triceratops')
                ->setLength(10)
                ->setEnclosure( 'Paddock B'),
        ];

        foreach ($dinos as $dino){
            $dino->setHealth($github->getHealthReport($dino->getName()));
        }

        return $this->render('main/index.html.twig', [
            'dinos' => $dinos,
            'controller_name' => 'MainController',
        ]);
    }
}
