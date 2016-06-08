<?php
namespace Hawk\ApiBundle\EventListener;
use Hawk\ApiBundle\Event\GroupMessage;
use Hawk\ApiBundle\Event\Message;
use Hawk\ApiBundle\Services\HawkApi;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageSubscriber implements EventSubscriberInterface
{
    /**
     * @var HawkApi
     */
    private $api = null;

    /**
     * @param HawkApi $api
     */
    public function setApi(HawkApi $api)
    {
        $this->api = $api;
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            Message::NEW_MESSAGE => array('onMessage', 0),
            GroupMessage::NEW_MESSAGE => array('onGroupMessage', 0),
        );
    }

    /**
     * Отправка сообщения одному пользователю
     * @param Message $message
     * @return $this
     * @throws \Exception
     */
    public function onMessage(Message $message)
    {
        $from = $this->getId($message->getFrom());
        $to = $this->getId($message->getTo());

        $message->setResult($this
            ->api
            ->getApi()
            ->sendMessage($from, $to, $message->getText(), $message->getEvent(), $message->getDomains())
            ->execute()
        );
    }

    /**
     * Отправка сообщения группе
     * @param GroupMessage $message
     * @return $this
     * @throws \Exception
     */
    public function onGroupMessage(GroupMessage $message)
    {
        $from = $this->getId($message->getFrom());
        $message->setResult($this
            ->api
            ->getApi()
            ->sendGroupMessage($from, $message->getText(), $message->getGroups(), $message->getEvent(), $message->getDomains())
            ->execute()
        );
    }

    private function getId($item)
    {
        if($item instanceof UserInterface)
        {
            return $this->api->setUser($item)->getUserId();
        }

        return $item;
    }
}