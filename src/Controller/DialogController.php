<?php

namespace App\Controller;

use App\Services\NewsService;
use App\Services\TrollService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DialogController extends AbstractController
{
    public function defaultAction(
        Request      $request,
        TrollService $trollService,
        NewsService  $newsService,
    ): Response
    {
        $nomDuBobby = $request->get('bobby', null);

        return $this->render('index.html.twig', [
            'troll' => $trollService->getTroll($nomDuBobby),
            'articles' => $newsService->getNews(),
            'user' => [
                'name' => 'Bobby',
                'age' => '42',
            ],
        ]);
    }

    public function apiAction(
        Request      $request,
        TrollService $trollService
    ): JsonResponse
    {
        $nomDuBobby = $request->get('bobby', null);

        return $this->json([
            'message' => $trollService->getTroll($nomDuBobby),
        ]);
    }
}