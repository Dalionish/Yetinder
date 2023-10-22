<?php

namespace App\Controller;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BestOfController extends AbstractController
{
    #[Route('/BestOf', name: 'best_of')]
    public function bestOf(DatabaseService $data): Response
    {
        $yeti = $data->selectBestYeti();
        return $this->render('yetinder/best_of.html.twig', [
            'yeti' => $yeti,
        ]);
    }
}