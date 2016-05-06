<?php
namespace Hawk\ApiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \Hawk\ApiBundle\HawkApi as BaseApi;
use Symfony\Component\Security\Core\User\UserInterface;

class HawkApi
{
    /**
     * @var BaseApi
     */
    private $api = null;
    /**
     * @var ContainerInterface
     */
    private $container = null;

    /**
     * @var string
     */
    private $salt = 'asm,234fa65msfm~,asn!^f,a^%sm/fn,s/';

    /**
     * @var UserInterface
     */
    private $user = null;

    /**
     * HawkApi constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    function __call($name, $arguments)
    {
        //проксируем все запросы к апи
        return call_user_func_array([$this->getApi(), $name], $arguments);
    }

    public function getApi()
    {
        if($this->api === null)
        {
            $port = $this->container->getParameter('hawk_api.client.port');
            $url = 'http' . ($this->container->getParameter('hawk_api.client.https') ? 's' : '') . '://'
                . $this->container->getParameter('hawk_api.client.host')
                . ($port ? (':' . $port) : '')
            ;

            $this->api = new BaseApi(
                $this->container->getParameter('hawk_api.client.key'),
                $url
            );
        }

        return $this->api;
    }

    public function getUserId()
    {
        if($this->user === null)
        {
            if (!$this->container->has('security.token_storage')) {
                throw new \LogicException('The SecurityBundle is not registered in your application.');
            }

            if (null === $token = $this->container->get('security.token_storage')->getToken()) {
                return null;
            }

            if (!is_object($user = $token->getUser())) {
                // e.g. anonymous authentication
                return null;
            }

            $this->user = $user;
        }

        return md5($this->user->getId() . $this->salt);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}