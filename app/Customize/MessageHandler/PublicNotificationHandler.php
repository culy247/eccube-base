<?php

namespace Customize\MessageHandler;

use Customize\Message\SmsNotification;

class PublicNotificationHandler extends NotificationHandler
{
    public function __invoke(SmsNotification $message)
    {
        return $this->publish('message', ['type' => 1, 'content' => $message->getContent()]);
    }
}
