<?php

namespace App\Controller;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends AbstractController
{
    public function statistics(DatabaseService $data, string $slug): Response
    {
        $data = $data->statistics();
        return $this->render('yetinder/statistics.html.twig', [
            'data' => $data,
            'slug' => $slug,
        ]);
    }
}