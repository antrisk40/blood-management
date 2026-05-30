<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ReceiverFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!isset($request->user) || $request->user['role'] !== 'receiver') {
            $response = service('response');
            return $response->setJSON([
                'success' => false,
                'message' => 'Forbidden. Only receivers can access this resource.'
            ])->setStatusCode(403);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
