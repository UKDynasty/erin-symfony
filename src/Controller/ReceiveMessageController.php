<?php
namespace App\Controller;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\GroupMe\DirectMessage;
use App\Service\DraftManager;
use App\Service\Erin;
use App\Service\GroupMe;
use Psr\Log\LoggerInterface;
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
    public function test(DraftManager $draftManager)
    {
         $pickOnClock = $draftManager->getPickOnClock($draftManager->getCurrentDraft());
         dump($pickOnClock);
    }


    /**
     * @Route("/direct")
     * @Method("POST")
     * @param Request $request
     * @param Erin $erin
     * @return JsonResponse
     */
    public function direct(Request $request, Erin $erin, GroupMe $groupMe, LoggerInterface $logger)
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