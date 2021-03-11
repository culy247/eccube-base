<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Command\Messenger;

use Customize\Mercure\JwtProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Component\HttpClient\EventSourceHttpClient;

class MessengerEventSourceCommand extends Command
{
    protected static $defaultName = 'app:messenger:event:listen';

    const SUBCRIPTION_TYPE = 'Subscription';

    private $tokenProvider;

    private $client;

    private $source;

    private $_started = false;

    public function __construct(JwtProvider $tokenProvider)
    {
        parent::__construct();
        $this->tokenProvider = $tokenProvider;
        $this->init();
    }

    protected function init()
    {
        $this->client = new EventSourceHttpClient(null, 3600);
        $hub = $this->buildUrl();
        $this->source = $this->client->connect($hub, [
            'auth_bearer' => $this->tokenProvider->getSubcribleToken(),
        ]);
        $this->_started = true;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        log_info('MessengerEventSourceCommand start');

        while ($this->source) {
            foreach ($this->client->stream($this->source, 2) as $r => $chunk) {
                if ($chunk->isTimeout()) {
                    // do something ...
                    //$this->_started = false;
                    //$this->source = null;

                    continue;
                }
                if ($chunk->isLast()) {
                    // do something ...
                    //return 0;
                    continue;
                }

                // this is a special ServerSentEvent chunk holding the pushed message
                if ($chunk instanceof ServerSentEvent) {
                    $this->processEvent($chunk->getId(), $chunk->getType(), $chunk->getRetry(), $chunk->getData());
                }
            }
        }

        //$this->_started = false;

        log_info('MessengerEventSourceCommand end');

        return 1;
    }

    // arg data is string
    protected function processEvent($eventId, $eventType, $retry, $data)
    {
        log_info('MessengerEventSourceCommand', [
            'id' => $eventId,
            'type' => $eventType,
            'retry' => $retry,
            'data' => $data,
        ]);
        try {
            $data = json_decode($data);
        } catch (\Exception $e) {
            $data = (object) $data;
        }
        if (isset($data->subscriber) && isset($data->type) && $data->type === self::SUBCRIPTION_TYPE) {
            $this->processEventSubcription($eventId, $eventType, $retry, $data);
        } else {
            $this->processEventMessage($eventId, $eventType, $retry, $data);
        }
    }

    // arg data is object
    protected function processEventSubcription($eventId, $eventType, $retry, $data)
    {
        //online: active => true, offline: active => false
        $isActive = $data->isActive ?? false;
        // url use for get by http request
        $subcriptionId = $data->id ?? '';
        // subcriber id
        $subscriber = $data->subscriber ?? '';
        // suncription topic
        $topic = $data->topic ?? '';
    }

    // arg data is object
    protected function processEventMessage($eventId, $eventType, $retry, $data)
    {
        //process message event here
    }

    protected function buildUrl()
    {
        $url = $this->tokenProvider->getHub();
        $topics = $this->tokenProvider->getSubcriptionTopics();
        $query = '?time='.time();
        foreach ($topics as $topic) {
            $query .= '&topic='.$topic;
        }

        return $url.$query;
    }
}
