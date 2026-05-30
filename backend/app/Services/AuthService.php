<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\HospitalRepository;
use App\Repositories\ReceiverRepository;
use App\Libraries\JwtLibrary;

class AuthService
{
    protected $userRepo;
    protected $hospitalRepo;
    protected $receiverRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->hospitalRepo = new HospitalRepository();
        $this->receiverRepo = new ReceiverRepository();
    }

    public function registerHospital(array $data)
    {
        $this->validateUniqueUser($data['email'], $data['username']);

        $db = \Config\Database::connect();
        $db->transStart();

        $userId = $this->userRepo->create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => 'hospital'
        ]);

        $this->hospitalRepo->create([
            'user_id'       => $userId,
            'hospital_name' => $data['hospital_name'],
            'address'       => $data['address'],
            'phone'         => $data['phone']
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Failed to register hospital');
        }

        return $userId;
    }

    public function registerReceiver(array $data)
    {
        $this->validateUniqueUser($data['email'], $data['username']);

        $db = \Config\Database::connect();
        $db->transStart();

        $userId = $this->userRepo->create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => 'receiver'
        ]);

        $this->receiverRepo->create([
            'user_id'     => $userId,
            'full_name'   => $data['full_name'],
            'blood_group' => $data['blood_group'],
            'phone'       => $data['phone']
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Failed to register receiver');
        }

        return $userId;
    }

    public function login(string $usernameOrEmail, string $password)
    {
        // Find by email or username
        $user = clone $this->userRepo;
        $userData = $this->userRepo->findByEmail($usernameOrEmail);
        if (!$userData) {
            $userData = $this->userRepo->findByUsername($usernameOrEmail);
        }

        if (!$userData || !password_verify($password, $userData['password'])) {
            throw new \Exception('Invalid credentials');
        }

        // Fetch role specific ID
        $profileId = null;
        if ($userData['role'] === 'hospital') {
            $hospital = $this->hospitalRepo->findByUserId($userData['id']);
            $profileId = $hospital['id'] ?? null;
        } else {
            $receiver = $this->receiverRepo->findByUserId($userData['id']);
            $profileId = $receiver['id'] ?? null;
        }

        $payload = [
            'id' => $userData['id'],
            'profile_id' => $profileId,
            'username' => $userData['username'],
            'role' => $userData['role']
        ];

        return [
            'token' => JwtLibrary::generateToken($payload),
            'user'  => $payload
        ];
    }

    private function validateUniqueUser(string $email, string $username)
    {
        if ($this->userRepo->findByEmail($email)) {
            throw new \Exception("Email already exists");
        }
        if ($this->userRepo->findByUsername($username)) {
            throw new \Exception("Username already exists");
        }
    }
}
