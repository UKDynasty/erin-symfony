<?php

namespace App\Controller;

use App\Repository\FranchiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FranchiseController extends AbstractController
{
    /**
     * @var FranchiseRepository
     */
    private $franchiseRepository;

    public function __construct(FranchiseRepository $franchiseRepository)
    {
        $this->franchiseRepository = $franchiseRepository;
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        $franchises = $this->franchiseRepository->findAllWithPlayers();


        return $this->render('franchise/index.html.twig', [
            'franchises' => $franchises,
        ]);
    }
}
