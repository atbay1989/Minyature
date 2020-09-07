<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Message;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class MessageController extends Controller
{
    /**
     * Render the home page
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function store(Request $request, Response $response, $args)
    {
        ['email' => $email, 'message' => $message] = $request->getParsedBody();

        $message = Message::create(['message' => $message]);

        return $response->withHeader('Location', '/');
    }
}
