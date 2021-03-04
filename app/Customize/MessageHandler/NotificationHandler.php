<?php

namespace Customize\MessageHandler;

use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

abstract class NotificationHandler extends BaseHandler
{
    protected $publisher;

    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function publish(string $topic, array $params)
    {
        $update = new Update(
            $topic,
            json_encode($params)
        );

        $pub = $this->getPublisher();
        // The Publisher service is an invokable object
        return $pub($update);
    }
}
