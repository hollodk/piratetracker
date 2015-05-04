<?php

namespace Hollo\TrackerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Hollo\TrackerBundle\Entity\Position;

class PositionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tracker:position')
            ->setDescription('Add a random position')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();
        $key = array_rand($users);

        $position = array(
            'lat' => 123,
            'lon' => 123
        );
        $json = json_encode($position);

        $ch = curl_init('http://dev.hollo.dk/piratetracker/web/app_dev.php/position');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $r = curl_exec($ch);
        var_dump($r);

        curl_close($ch);
    }
}
