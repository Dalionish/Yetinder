<?php

namespace App\Service;

use DateTime;
use App\Entity\Yeti;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FormHandleService extends AbstractController
{
    public function __construct(private DatabaseService $database, private UserPasswordHasherInterface $passwordHasher, private Security $security)
    {
    }

    // Formular pro pridani noveho yeti
    public function handleAddForm(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Yeti $yeti */
            $yeti = $form->getData();
            $DateTime = new DateTime();
            $timestamp = $DateTime->getTimestamp();
            $yeti->setAdded($timestamp);

            $photo = $form->get('photoFile')->getData();
            $newFileName = md5(uniqid()) . '.' . $photo->guessExtension();
            $photo->move($this->getParameter('photos_directory'), $newFileName);
            $yeti->setPhotoFilename($newFileName);

            $user = $this->security->getUser();
            $userEmail = $user->getEmail();
            $userID = $this->database->getUserID($userEmail);
            $yeti->setUserID($userID);

            $this->database->yetiInsert($yeti);

            return true;
        }
        return false;
    }

    // Formular pro registraci noveho uzivatele
    public function handleRegistrationForm(FormInterface $registerForm, Request $request): bool
    {
        if ($request->request->has('register_form')) {
            $registerForm->handleRequest($request);
            if ($registerForm->isSubmitted() && $registerForm->isValid()) {

                $user = $registerForm->getData();
                $plaintextPassword = $registerForm['password']->getData();

                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                $this->database->userInsert($user);
                return true;
            }
            return false;
        }
        return false;
    }

}