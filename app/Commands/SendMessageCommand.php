<?php

namespace App\Commands;

use App\Models\Message;

class SendMessageCommand
{
    public $email;
    public $messsage;

    public function __construct($email, Message $message)
    {
        $this->email = $email;
        $this->message = $message;
    }
}
