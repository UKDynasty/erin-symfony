<?php
namespace App\Controller;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\GroupMe\DirectMessage;
use App\Service\Erin;
use App\Service\GroupMe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReceiveMessageController
 * @package App\Controller
 * @Route("/talk")
 */
class ReceiveMessageController extends Controller
{

    /**
     * @Route("/test")
     * @Method("GET")
     * @param GroupMe $groupMe
     * @return JsonResponse
     */
    public function test(GroupMe $groupMe)
    {
        $draftPickRepo = $this->getDoctrine()->getRepository(DraftPick::class);
        $franchiseRepo = $this->getDoctrine()->getRepository(Player::class);
        $franchise = $this->getDoctrine()->getRepository(Franchise::class)->findOneBy(["mflFranchiseId" => "0003"]);
        foreach($draftPickRepo->getUnusedPicksForFranchise($franchise) as $pick) {
            echo $pick;
        }
        return new Response("<html><body></body></html>");
    }


    /**
     * @Route("/direct")
     * @Method("POST")
     * @param Request $request
     * @param Erin $erin
     * @return JsonResponse
     */
    public function direct(Request $request, Erin $erin, GroupMe $groupMe)
    {
        $groupMeMessage = json_decode($request->getContent(), true);

        $result = $erin->receiveDirectMessage($groupMeMessage);

        return new JsonResponse(["success" => (bool)$result]);
    }

    /**
     * @Route("/group")
     * @Method("POST")
     */
    public function group(Request $request, Erin $erin, GroupMe $groupMe)
    {
        $groupMeMessage = json_decode($request->getContent(), true);

        $result = $erin->receiveGroupMessage($groupMeMessage);

        return new JsonResponse(["success" => (bool)$result]);
    }
}