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
     * @Route("/token", name="hawk_token", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getTokenAction(Request $request)
    {
        $result = null;
        if($this->getUser())
        {
            $id = $this->getApi()->getUserId();

            $token = $this
                ->getApi()
                ->registerUser($id)
                ->getToken($id, $this->getApi()->getSalt())
                ->execute()
                ->getResult('getToken')
            ;

            if(!$this->getApi()->hasErrors() && isset($token[0]['result']))
            {
                $result = [
                    'result' => [
                        'token' => $token[0]['result'][$request->getHost()],
                        'id' => $id,
                        'ws' => 'ws' . ($this->container->getParameter('hawk_api.client.https') ? 's' : '')
                            . '://' . $this->container->getParameter('hawk_api.client.host')
                            . ':' . $this->container->getParameter('hawk_api.client.port')
                    ],
                    'errors' => false
                ];
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
