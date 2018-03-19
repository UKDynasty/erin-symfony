<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 19/03/18
 * Time: 14:19
 */

namespace App\Controller;
use App\Form\SandboxFormType;
use App\Service\Erin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SandboxController
 * @package App\Controller
 */
class SandboxController extends AbstractController
{
    /**
     * @Route("/sandbox", methods={"GET", "POST"})
     * @param Request $request
     * @param Erin $erin
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function talk(Request $request, Erin $erin)
    {
        $form = $this->createForm(SandboxFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->render("sandbox.html.twig", [
                "reply" => $erin->receiveSandboxMessage($data["message"]),
                "form" => $form->createView(),
            ]);
        }

        return $this->render("sandbox.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}