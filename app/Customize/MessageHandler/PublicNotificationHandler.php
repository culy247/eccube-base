<?php

namespace Customize\MessageHandler;

use Customize\Message\AutionNotification;

class PublicNotificationHandler extends NotificationHandler
{
    public function __invoke(AutionNotification $message)
    {
        return $this->publish($message->getTopics(), $message->getPayload(), $message->getPrivate());
    }
}
