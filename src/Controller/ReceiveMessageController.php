<?php
namespace App\Controller;

use App\GroupMe\DirectMessage;
use App\Service\Erin;
use App\Service\GroupMe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param Erin $erin
     * @return JsonResponse
     */
    public function test(Request $request, Erin $erin, GroupMe $groupMe)
    {
        $res = $erin->receiveDirectMessage(["sender_id" => "36266918", "text" => "pick 2.02"]);
        return new JsonResponse($res);
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