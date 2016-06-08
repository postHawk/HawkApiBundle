<?php
/**
 * Created by PhpStorm.
 * User: Maximilian
 * Date: 08.06.2016
 * Time: 10:26
 */

namespace Hawk\ApiBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Hawk\Api\HawkApi;

class GroupMessage extends Event
{
    const NEW_MESSAGE = 'group_message';

    /**
     * От кого
     * @var String
     */
    private $from = null;

    /**
     * Сообщение
     * @var String
     */
    private $text = null;

    /**
     * В какие группы
     * @var String
     */
    private $groups = null;

    /**
     * Событие
     * @var String
     */
    private $event = null;

    /**
     * На какие домены
     * @var String
     */
    private $domains = null;

    /**
     * @var HawkApi
     */
    private $result = null;

    /**
     * @param String $from
     * @return GroupMessage
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param String $text
     * @return GroupMessage
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param String $groups
     * @return GroupMessage
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @param String $event
     * @return GroupMessage
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @param String $domains
     * @return GroupMessage
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return String
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return String
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return String
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