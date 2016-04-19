<?php

namespace Hollo\TrackerBundle\Helper;

class GcmHelper
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function sendMessage($body)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $client = $this->container->get('endroid.gcm.client');

        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();

        $regIds = array();
        foreach ($users as $user) {
            if (strlen($user->getGcmNumber()) > 0 && !in_array($user->getGcmNumber(), $regIds)) {
                $regIds[] = $user->getGcmNumber();
            }
        }

        $response = $client->send($body, $regIds);

        return $response;
    }
}
