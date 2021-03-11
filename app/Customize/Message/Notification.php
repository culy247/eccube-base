<?php

namespace Customize\Message;

class Notification extends Base
{
    protected $payload;

    protected $topics = 'message';

    protected $private = false;

    public function __construct($payload, string $topics = 'message', bool $private = false)
    {
        $this->payload = $payload;
        $this->topics = $topics;
        $this->private = $private;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getTopics()
    {
        return $this->topics;
    }

    public function setTopics($topics)
    {
        $this->topics = $topics;

        return $this;
    }

    public function getPrivate()
    {
        return (bool) $this->private;
    }

    public function setPrivate(bool $private)
    {
        $this->private = $private;

        return $this;
    }
}
