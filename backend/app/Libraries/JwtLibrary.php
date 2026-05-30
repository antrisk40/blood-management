<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtLibrary
{
    private static function getSecretKey()
    {
        return getenv('JWT_SECRET') ?: 'default-secret-key-change-in-production';
    }

    public static function generateToken(array $payload, int $expiryInSeconds = 3600): string
    {
        $key = self::getSecretKey();
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiryInSeconds;

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function validateToken(string $token): ?array
    {
        try {
            $key = self::getSecretKey();
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
