<?php
namespace App\Controller;

use App\Service\Erin;
use App\Service\GroupMe;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReceiveMessageController
 * @package App\Controller
 * @Route("/talk")
 */
class ReceiveMessageController extends AbstractController
{
    /**
     * @Route("/direct", methods={"POST"})
     * @param Request $request
     * @param Erin $erin
     * @param GroupMe $groupMe
     * @param LoggerInterface $logger
     * @return JsonResponse
     * @throws \Exception
     */
    public function direct(Request $request, Erin $erin, GroupMe $groupMe, LoggerInterface $logger)
    {
        $groupMeMessage = json_decode($request->getContent(), true);

        $result = $erin->receiveDirectMessage($groupMeMessage);

        return new JsonResponse(["success" => (bool)$result]);
    }

    /**
     * @Route("/group", methods={"POST"})
     */
    public function group(Request $request, Erin $erin, GroupMe $groupMe)
    {
        $groupMeMessage = json_decode($request->getContent(), true);

        $result = $erin->receiveGroupMessage($groupMeMessage);

        return new JsonResponse(["success" => (bool)$result]);
    }
}
