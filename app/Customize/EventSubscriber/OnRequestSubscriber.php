<?php

namespace Customize\EventSubscriber;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class OnRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->bus->dispatch(
            new \Customize\Message\AutionNotification([
                'type' => 1,
                'user_at' => date('Y-m-d H:i:s'),
            ], 'message', false)
        );

        /*$request = $event->getRequest();
        $cookies = $request->cookies;
        if (!$cookies->has('mercureAuthorization')) {
            $key = Key\InMemory::plainText('!ChangeMe!');
            $configuration = Configuration::forSymmetricSigner(new Sha256(), $key);

            $token = $configuration->builder()
                ->withClaim('mercure', [
                    'subscribe' => ['message'],
                    'publish' => ['message'],
                ])
                ->getToken($configuration->signer(), $configuration->signingKey())
                ->toString();

            $cookie = Cookie::create('mercureAuthorization', $token, 0, '/hub', null, true, true, false, 'strict');

            $response = $event->getResponse();

            $response->headers->setCookie($cookie);

            return $response;
        }*/
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
