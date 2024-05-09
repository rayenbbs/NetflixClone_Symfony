<?php

namespace App\Service;

use App\Entity\User; // Your User entity


class AccountService
{

    public function __construct()
    {

    }

    public function updateUserDetails(User $user, string $firstName, string $lastName, string $email): bool
    {
        // Update user details
        try {
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);

            // Save the changes (this requires Doctrine persistence context)
            return true;
        } catch (\Exception $e) {
            return false; // Handle error appropriately
        }
    }

    public function updatePassword(User $user, string $oldPassword, string $newPassword): bool
    {
        // Update user password
        try {
            // Check if the old password is correct
            if (!password_verify($oldPassword, $user->getPassword())) {
                throw new \Exception('Old password is incorrect');
            }

            // Set the new password
            $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));

            // Save the changes (this requires Doctrine persistence context)
            return true;
        } catch (\Exception $e) {
            return false; // Handle error appropriately
        }
    }
}
