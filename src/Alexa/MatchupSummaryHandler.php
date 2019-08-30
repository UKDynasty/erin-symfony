<?php
namespace App\Alexa;

use App\Entity\Matchup;
use App\Repository\MatchupRepository;
use App\Service\Erin;
use App\Service\MessageDataExtractor;
use App\Service\ScheduleManager;
use MaxBeckers\AmazonAlexa\Helper\ResponseHelper;
use MaxBeckers\AmazonAlexa\Helper\SsmlGenerator;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\RequestHandler\AbstractRequestHandler;
use MaxBeckers\AmazonAlexa\Response\Response;

class MatchupSummaryHandler extends AbstractRequestHandler
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;
    /**
     * @var Erin
     */
    private $erin;
    /**
     * @var ScheduleManager
     */
    private $scheduleManager;
    /**
     * @var MatchupRepository
     */
    private $matchupRepository;
    /**
     * @var MessageDataExtractor
     */
    private $messageDataExtractor;
    /**
     * @var SsmlGenerator
     */
    private $ssmlGenerator;

    public function __construct(ResponseHelper $responseHelper, ScheduleManager $scheduleManager, MatchupRepository $matchupRepository, MessageDataExtractor $messageDataExtractor, SsmlGenerator $ssmlGenerator)
    {

        $this->responseHelper = $responseHelper;

        $this->supportedApplicationIds = ['amzn1.ask.skill.fe61c251-70fe-43c6-9cd3-0b315b4ce327'];
        $this->scheduleManager = $scheduleManager;
        $this->matchupRepository = $matchupRepository;
        $this->messageDataExtractor = $messageDataExtractor;
        $this->ssmlGenerator = $ssmlGenerator;
    }

    public function supportsRequest(Request $request): bool
    {
        return $request->request instanceOf IntentRequest &&
            'MatchupSummaryIntent' === $request->request->intent->name;
    }

    public function handleRequest(Request $request): Response
    {
        $week = $this->scheduleManager->getCurrentWeek();

        if (isset($request->request->intent->slots[0])) {
            $franchise = $this->messageDataExtractor->extractFranchise($request->request->intent->slots[0]->value);
        }
        $matchups = $this->matchupRepository->findByWeek($week, $franchise ?? null);

        foreach($matchups as $matchup) {
            $this->ssmlGenerator->say($matchup->toStringForAlexa());
            $this->ssmlGenerator->pauseTime('0.75s');
        }

       return $this->responseHelper->respond($this->ssmlGenerator->getSsml());
    }
}
