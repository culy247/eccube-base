<?php

namespace Customize\MessageHandler;

use Customize\Message\AutionNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class LoopNotificationHandler extends BaseHandler
{
    protected $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(AutionNotification $message)
    {
        sleep(30);

        $topics = ['message', 'aution_message', 'aution_start', 'aution_end', 'aution_warning'];

        $topic = $topics[array_rand($topics)];
        $payload = $message->getPayload();
        $payload['type'] = $topic;
        $payload['current'] = microtime(true);
        $message->setPayload($payload);
        $message->setTopics($topic);

        $this->bus->dispatch(
            $message
        );
    }
}
