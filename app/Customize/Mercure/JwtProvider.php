<?php

namespace Customize\Mercure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

final class JwtProvider
{
    private $jwtKey;
    private $publishTopics;
    private $subcriptionTopic;
    private $userId;
    private $hub;

    public function __construct($jwtKey, $publishTopic, $subcriptionTopic, $hub = '', $userId = 0)
    {
        $this->jwtKey = $jwtKey;
        $this->publishTopics = explode(',', $publishTopic);
        $this->subcriptionTopic = $this->parseTopic($subcriptionTopic);
        $this->userId = $userId;
        $this->hub = $hub;
    }

    public function parseTopic($topics)
    {
        if (!is_array($topics)) {
            $topics = explode(',', $topics);
        }

        return $topics;
    }

    public function getHub()
    {
        return $this->hub;
    }

    public function setHub($hub)
    {
        $this->hub = $hub;

        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function getJwtKey()
    {
        return $this->jwtKey;
    }

    public function setJwtKey($jwtKey)
    {
        $this->jwtKey = $jwtKey;

        return $this;
    }

    public function getPublishTopics()
    {
        return $this->publishTopics;
    }

    public function setPublishTopics($topics = [])
    {
        $this->publishTopics = $topics;

        return $this;
    }

    public function getSubcriptionTopics()
    {
        return $this->subcriptionTopic;
    }

    public function setSubcriptionTopics($topics = [])
    {
        $this->subcriptionTopic = $topics;

        return $this;
    }

    public function __invoke(): string
    {
        return $this->getToken();
    }

    public function getToken()
    {
        return $this->_getToken([
            'subscribe' => $this->getSubcriptionTopics(),
            'publish' => $this->getPublishTopics(),
        ]);
    }

    public function getSubcribleToken()
    {
        return $this->_getToken([
            'subscribe' => $this->getSubcriptionTopics(),
        ]);
    }

    public function getPublishToken()
    {
        return $this->_getToken([
            'publish' => $this->getPublishTopics(),
        ]);
    }

    private function _getToken(array $mercureClam)
    {
        $mercureClam['_m_mtime'] = microtime(true);
        $mercureClam['_m_time'] = time();
        $mercureClam['_m_user_id'] = $this->getUserId();
        $key = Key\InMemory::plainText($this->getJwtKey());
        $configuration = Configuration::forSymmetricSigner(new Sha256(), $key);
        $timeZone = new \DateTimeZone('UTC');
        $now = new \DateTimeImmutable('now', $timeZone);
        $token = $configuration->builder(new UnixTimestampDateConversion())
                ->issuedAt($now)
                //->setNotBefore(time()) // payload
                ->expiresAt($this->getTokenExpire($now))
                ->withClaim('mercure', $mercureClam)
                ->getToken($configuration->signer(), $configuration->signingKey())
                ->toString();

        return $token;
    }

    private function getTokenExpire(\DateTimeImmutable $now)
    {
        return $now->modify('+1 hours');
    }
}
