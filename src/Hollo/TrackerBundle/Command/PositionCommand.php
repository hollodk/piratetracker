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

        $data = array(
            'lat' => 123,
            'lon' => 123
        );

        $ch = curl_init('http://localhost/~mh/piratetracker/web/app_dev.php/position');
        $http_string = http_build_query($data);

        curl_setopt($ch, CURLOPT_USERPWD, 'tonne:luder');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $http_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $r = curl_exec($ch);
        curl_close($ch);
    }
}
