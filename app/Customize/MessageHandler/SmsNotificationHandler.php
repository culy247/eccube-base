<?php

namespace Customize\MessageHandler;

use Customize\Message\SmsNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsNotificationHandler extends BaseHandler
{
    protected $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(SmsNotification $message)
    {
        sleep(1);
        $message->setContent($message->getContent().' - '.microtime());
        $this->bus->dispatch(
            $message
        );
    }
}
