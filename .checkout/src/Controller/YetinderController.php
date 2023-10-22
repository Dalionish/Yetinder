<?php

namespace App\Controller;

use App\Service\DatabaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YetinderController extends AbstractController
{
    #[Route('/Yetinder', name: 'yetinder')]
    public function yetinder(DatabaseService $data, Security $security): Response
    {
        //Vyber yeti k hodnoceni (pro neprihlaseneho uzivatele = random)
        if (!($user = $security->getUser())) {
            $yeti = $data->yetinderSelectRandom();
        } else {
            $userEmail = $user->getEmail();
            $userID = $data->getUserID($userEmail);
            $yeti = $data->yetinderSelect($userID);
        }
        return $this->render('yetinder/yetinder.html.twig', [
            'yeti' => $yeti
        ]);
    }

    //Ulozeni hodnoceni yeti
    public function yetinderRating(Request $request, DatabaseService $database): JsonResponse
    {
        $userRating = (int)$request->request->get('userRating');
        $userEmail = $request->request->get('userEmail');
        $yetiID = $request->request->get('yetiID');
        $userID = $database->getUserID($userEmail);
        $database->ratingInsert($userRating, $userID, $yetiID);
        return new JsonResponse();
    }
}