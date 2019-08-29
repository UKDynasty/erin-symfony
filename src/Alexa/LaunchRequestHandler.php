<?php
namespace App\Alexa;

use App\Service\Erin;
use MaxBeckers\AmazonAlexa\Helper\ResponseHelper;
use MaxBeckers\AmazonAlexa\Helper\SsmlGenerator;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\RequestHandler\AbstractRequestHandler;
use MaxBeckers\AmazonAlexa\Response\Response;

class LaunchRequestHandler extends AbstractRequestHandler
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;

    public function __construct(ResponseHelper $responseHelper)
    {

        $this->responseHelper = $responseHelper;

        $this->supportedApplicationIds = ['amzn1.ask.skill.fe61c251-70fe-43c6-9cd3-0b315b4ce327'];
    }

    public function supportsRequest(Request $request): bool
    {
        return $request->request instanceOf Request\Standard\LaunchRequest;
    }

    public function handleRequest(Request $request): Response
    {
        return $this->responseHelper->respond("Hey, for now you can only ask me who owns a player. I'll learn more soon!");
    }
}
