<?php

namespace Hawk\ApiBundle\Controller;

use Hawk\Api\HawkApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HawkController extends Controller
{
    /**
     * @var HawkApi
     */
    private $api = null;


    /**
     * @Route("/token/{useSessionId}", name="hawk_token", defaults={"useSessionId": false}, options={"expose":true})
     * @param Request $request
     * @param bool $useSessionId использвоать id сессии
     * @return JsonResponse
     * @throws \Exception
     */
    public function getTokenAction(Request $request, $useSessionId = false)
    {
        $result = null;
        $id = null;
        if($useSessionId)
        {
            $id = $this->container->get('session')->getId();
        }
        else
        {
            $id = $this->getApi()->getUserId();
        }

        if($id)
        {
            $token = $this
                ->getApi()
                ->registerUser($id)
                ->getToken($id, $this->getApi()->getSalt())
                ->execute()
                ->getResult('getToken')
            ;

            if(!$this->getApi()->hasErrors() && isset($token[0]['result']))
            {
                $result = $token[0]['result'];
                $key = $request->getHost();
                if(!isset($result[$key]))
                {
                    $key = $key . ':' . $request->getPort();
                    if(!isset($result[$key]))
                    {
                        $key = key($result);
                    }
                }

                if(isset($result[$key]))
                {
                    $result = [
                        'result' => [
                            'token' => $result[$key],
                            'id' => $id,
                            'ws' => 'ws' . ($this->container->getParameter('hawk_api.client.https') ? 's' : '')
                                . '://' . $this->container->getParameter('hawk_api.client.host')
                                . ':' . $this->container->getParameter('hawk_api.client.port')
                        ],
                        'errors' => false
                    ];
                }
                else
                {
                    $result = [
                        'result' => false,
                        'errors' => 'Не удаётся определить текущий хост'
                    ];
                }
            }
            elseif ($this->getApi()->hasErrors())
            {
                $result = [
                    'result' => false,
                    'errors' => $this->getApi()->getErrors()
                ];
            }
        }
        else
        {
            $result = [
                'result' => false,
                'errors' => 'no_user'
            ];
        }

        return new JsonResponse($result);
    }

    private function getApi()
    {
        if($this->api === null)
        {
            $this->api = $this->get('hawk_api.api');
        }

        return $this->api;
    }
}
