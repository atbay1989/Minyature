<?php

namespace App\Handlers;

use App\Commands\SendMessageCommand;
use Swift_Message;
use Slim\Views\Twig;
use Swift_Mailer;

class SendMessageHandler
{
    protected $view;
    protected $email;

    public function __construct(Twig $view, Swift_Mailer $email)
    {
        $this->view = $view;
        $this->email = $email;

    }

    public function handle(SendMessageCommand $command)
    {
        $message = (new Swift_Message('You have a new message sent with Minyature'))
            ->setFrom(['hello@minyature.com' => 'Minyature'])
            ->setTo($command->email)
            ->setBody(
                $this->view->fetch('email/message.twig', [
                    'message' => $command->message
                ]),
                'text/html'
            );

        $this->email->send($message);
    }
}