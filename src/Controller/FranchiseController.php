<?php

namespace App\Controller;

use App\Entity\Franchise;
use App\Repository\FranchiseRepository;
use App\Repository\PlayerRepository;
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

    /**
     * @Route("/franchises/{id}")
     * @param Franchise $franchise
     * @param PlayerRepository $playerRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Franchise $franchise, PlayerRepository $playerRepository)
    {
        return $this->render('franchise/show.html.twig', [
            'franchise' => $franchise,
            'roster' => $playerRepository->getPlayersForFranchiseOrdered($franchise),
        ]);
    }
}
