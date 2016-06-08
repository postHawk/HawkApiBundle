<?php

namespace Hawk\ApiBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Hawk\Api\HawkApi;

class Message extends Event
{
    const NEW_MESSAGE = 'message';

    /**
     * От кого
     * @var String
     */
    private $from = null;

    /**
     * Кому
     * @var String
     */
    private $to = null;

    /**
     * Сообщение
     * @var String
     */
    private $text = null;

    /**
     * Событие
     * @var String
     */
    private $event = null;

    /**
     * На какие домены
     * @var array
     */
    private $domains = [];

    /**
     * @var HawkApi
     */
    private $result = null;

    /**
     * @param String $from
     * @return Message
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param String $to
     * @return Message
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param String $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param String $event
     * @return Message
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @param array $domains
     * @return Message
     */
    public function setDomains($domains)
    {
        $this->domains = $domains;
        return $this;
    }

    /**
     * @return String
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return String
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return String
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return String
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @return HawkApi
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param HawkApi $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}