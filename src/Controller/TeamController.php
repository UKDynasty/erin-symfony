<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team")
     */
    public function index()
    {
        /**
         * Display NFL teams' top-ranked players
         */

        $stmt = $this->getDoctrine()->getManager()->createQuery('SELECT DISTINCT(player.team) FROM App\\Entity\\Player player');
        $stmt->execute();
        $teamAbbr = array_column($stmt->getResult(), 1);

        return $this->render('team/index.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }
}
