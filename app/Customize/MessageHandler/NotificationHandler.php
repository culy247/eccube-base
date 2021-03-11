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

    public function publish(string $topic, $params, $private = false)
    {
        log_info('publish', [
            'topic' => $topic,
            'params' => $params,
            'private' => $private,
        ]);

        $update = new Update(
            $topic,
            json_encode($params),
            //$private
        );

        $pub = $this->getPublisher();
        // The Publisher service is an invokable object
        return $pub($update);
    }
}
