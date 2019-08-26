<?php
namespace App\Alexa;

use App\Service\Erin;
use MaxBeckers\AmazonAlexa\Helper\ResponseHelper;
use MaxBeckers\AmazonAlexa\Helper\SsmlGenerator;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\RequestHandler\AbstractRequestHandler;
use MaxBeckers\AmazonAlexa\Response\Response;

class SessionEndedRequestHandler extends AbstractRequestHandler
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    public function __construct(ResponseHelper $responseHelper)
    {

        $this->responseHelper = $responseHelper;

        $this->supportedApplicationIds = ['amzn1.ask.skill.5111ef87-31dc-461f-8455-23ca184dd885'];
    }

    public function supportsRequest(Request $request): bool
    {
        return $request->request instanceOf IntentRequest &&
            'SessionEndedRequestHandler' === $request->request->intent->name;
    }

    public function handleRequest(Request $request): Response
    {
        return $this->responseHelper->respond('Talk soon!');
    }
}
