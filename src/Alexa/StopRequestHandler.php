<?php
namespace App\Alexa;

use App\Service\Erin;
use MaxBeckers\AmazonAlexa\Helper\ResponseHelper;
use MaxBeckers\AmazonAlexa\Helper\SsmlGenerator;
use MaxBeckers\AmazonAlexa\Request\Request;
use MaxBeckers\AmazonAlexa\Request\Request\Standard\IntentRequest;
use MaxBeckers\AmazonAlexa\RequestHandler\AbstractRequestHandler;
use MaxBeckers\AmazonAlexa\Response\Response;

class StopRequestHandler extends AbstractRequestHandler
{
    /**
     * @var ResponseHelper
     */
    private $responseHelper;
    /**
     * @var Erin
     */
    private $erin;

    public function __construct(ResponseHelper $responseHelper)
    {

        $this->responseHelper = $responseHelper;

        $this->supportedApplicationIds = ['amzn1.ask.skill.fe61c251-70fe-43c6-9cd3-0b315b4ce327'];
    }

    public function supportsRequest(Request $request): bool
    {
        return $request->request instanceOf IntentRequest &&
            'AMAZON.StopIntent' === $request->request->intent->name;
    }

    public function handleRequest(Request $request): Response
    {
        return $this->responseHelper->respond('Talk soon!', true);
    }
}
