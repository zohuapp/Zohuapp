<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/credentials.json'));
        $this->auth = $factory->createAuth();
    }

    public function deleteUser($uid)
    {
        try {
            $this->auth->deleteUser($uid);
            return ['status' => 'success', 'message' => 'User deleted successfully.'];
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return ['status' => 'error', 'message' => 'User not found.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error deleting user.'];
        }
    }
}
