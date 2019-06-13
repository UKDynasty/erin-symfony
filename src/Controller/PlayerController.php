<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/player/{id}")
     * @param Player $player
     * @return Response
     */
    public function show(Player $player)
    {
        return $this->render('player/show.html.twig', [
            'player' => $player,
        ]);
    }

    /**
     * @Route("/free-agents")
     * @param PlayerRepository $playerRepository
     * @return Response
     */
    public function listFreeAgents(PlayerRepository $playerRepository)
    {
        $players = $playerRepository->getFreeAgentsOrderedByValue();

        return $this->render('player/free-agents.html.twig', [
            'players' => $players,
        ]);
    }
}
