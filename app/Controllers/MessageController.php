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

    public function show(Request $request, Response $response, $args)
    {
        ['uuid' => $uuid] = $args; 

        if ($message = Message::where('uuid', $uuid)->first()) {
            $message->delete();
        }
        return $this->c->get('view')->render($response, 'messages/show.twig', compact('message'));
    }

    public function store(Request $request, Response $response, $args)
    {
        ['email' => $email, 'message' => $message] = $request->getParsedBody();

        $message = Message::create([
            'message' => $message,

        ]);

        $this->c->get('flash')->addMessage('success', 'Message has been sent.');

        return $response->withHeader('Location', '/');
    }
}
