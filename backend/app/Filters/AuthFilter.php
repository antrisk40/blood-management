<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\JwtLibrary;
use CodeIgniter\API\ResponseTrait;

class AuthFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (empty($header) || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $response = service('response');
            return $response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access. Token not provided.'
            ])->setStatusCode(401);
        }

        $token = $matches[1];
        $decoded = JwtLibrary::validateToken($token);

        if (!$decoded) {
            $response = service('response');
            return $response->setJSON([
                'success' => false,
                'message' => 'Invalid or expired token.'
            ])->setStatusCode(401);
        }

        // Attach user info to the request to be used in controllers
        $request->user = $decoded;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
