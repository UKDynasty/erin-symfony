<?php
namespace App\Alexa;

use App\Service\Erin;
use MaxBeckers\AmazonAlexa\Helper\ResponseHelper;
use MaxBeckers\AmazonAlexa\Helper\SsmlGenerator;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\RequestHandler\AbstractRequestHandler;
use MaxBeckers\AmazonAlexa\Response\Response;

class WhoOwnsPlayerHandler extends AbstractRequestHandler
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;
    /**
     * @var Erin
     */
    private $erin;

    public function __construct(ResponseHelper $responseHelper, Erin $erin)
    {

        $this->responseHelper = $responseHelper;
        $this->erin = $erin;

        $this->supportedApplicationIds = ['amzn1.ask.skill.5111ef87-31dc-461f-8455-23ca184dd885'];
    }

    public function supportsRequest(Request $request): bool
    {
        return $request->request instanceOf IntentRequest &&
            'WhoOwnsPlayerIntent' === $request->request->intent->name;
    }

    public function handleRequest(Request $request): Response
    {
        $term = $request->request->intent->slots[0]->value;
        $erinResponse = $this->erin->receiveAlexaMessage("Who owns $term?");
        return $this->responseHelper->respond($erinResponse);
    }
}
