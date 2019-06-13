<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    /**
     * @Route("/player/{id}")
     * @param Player $player
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Player $player)
    {
        return $this->render('player/show.html.twig', [
            'player' => $player,
        ]);
    }
}
