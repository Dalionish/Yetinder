<?php

namespace App\Controller;

use App\Form\AddFormType;
use App\Service\FormHandleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddYetiController extends AbstractController
{
    #[Route('/Add', name: 'add')]
    public function add(Request $request, FormHandleService $formHandle): Response
    {
        $form = $this->createForm(AddFormType::class);
        if ($formHandle->handleAddForm($form, $request)) {
            $this->addFlash('success', 'Yetti přidán!');
            return $this->redirectToRoute('add');
        }
        return $this->render('yetinder/add.html.twig', [
            'addForm' => $form->createView(),
        ]);
    }
}