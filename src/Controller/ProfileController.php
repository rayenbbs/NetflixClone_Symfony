<?php

namespace App\Controller;

use App\Form\UserDetailsFormType; // Form type for user details
use App\Form\ChangePasswordFormType; // Form type for password change
use App\Service\AccountService; // Service for updating account details
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function settings(Request $request, AccountService $accountService): Response
    {
        // Create forms for user details and password update
        $user = $this->getUser(); // Get the logged-in user
        $detailsForm = $this->createForm(UserDetailsFormType::class, $user); // Form for user details
        $passwordForm = $this->createForm(ChangePasswordFormType::class); // Form for updating password

        $detailsForm->handleRequest($request);
        $passwordForm->handleRequest($request);

        $detailsMessage = '';
        $passwordMessage = '';

        // Handle updating user details
        if ($detailsForm->isSubmitted() && $detailsForm->isValid()) {
            $data = $detailsForm->getData();

            if ($accountService->updateUserDetails($user, $data['firstName'], $data['lastName'], $data['email'])) {
                $detailsMessage = 'Details updated successfully';
            } else {
                $detailsMessage = 'Error updating details'; // You can improve this to show specific error
            }
        }

        // Handle changing the password
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();

            if ($accountService->updatePassword($user, $data['oldPassword'], $data['newPassword'])) {
                $passwordMessage = 'Password updated successfully';
            } else {
                $passwordMessage = 'Error updating password'; // You can improve this to show specific error
            }
        }

        return $this->render('profile/index.html.twig', [
            'detailsForm' => $detailsForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'detailsMessage' => $detailsMessage,
            'passwordMessage' => $passwordMessage,
        ]);
    }
}
