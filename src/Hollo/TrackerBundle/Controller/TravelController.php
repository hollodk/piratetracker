<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\User;
use Hollo\TrackerBundle\Entity\Position;

/**
 * @Route("/travel")
 */
class TravelController extends Controller
{
    /**
     * @Route("")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $interval = new \DateInterval('PT1M');
        $res = array();
        $counter = array();
        $start = new \DateTime('2015-05-23 06:00:00');
        $end = new \DateTime('2015-05-24 05:00:00');

        while ($start <= $end) {
            $counter[] = array('time' => $start->format('m/d H:i'));

            $start->add($interval);
        }

        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();

        foreach ($users as $user) {
            $start = new \DateTime('2015-05-23 06:00:00');
            $end = new \DateTime('2015-05-24 05:00:00');
            $prevTicker = clone $start;

            $p = new \StdClass();
            $p->user = $user->getId();

            switch (true) {
            case ($user->getId() === 1 || $user->getId() === 3 || $user->getId() === 5):
                $p->rank = 'captain';
                break;

            case ($user->getId() == 4):
                $p->rank = 'ship';
                break;

            default:
                $p->rank = 'pirate';
                break;
            }

            $p->positions = array();

            $positions = $em->getRepository('HolloTrackerBundle:Position')->getRange($user, $start, $end);

            if (count($positions) === 0) {
                continue;
            }

            foreach ($positions as $position) {
                switch (true) {
                case ($position->getCreatedAt() < $prevTicker):
                    /*
                    var_dump($user->getId(), $start->format('m/d H:i:s'), $position->getCreatedAt()->format('m/d H:i:s'));
                    var_dump('skipping');
                     */

                    continue;

                case ($position->getCreatedAt() <= $start):
                    $p->positions[] = $this->buildStep($position, $user, $start);

                    /*
                    var_dump($user->getId(), $start->format('m/d H:i:s'), $position->getCreatedAt()->format('m/d H:i:s'));
                    var_dump('added, past');
                     */
                    $start->add($interval);

                    break;

                case ($position->getCreatedAt() > $start):
                    while ($position->getCreatedAt() > $start) {

                        if (!isset($i)) {
                            $i = new \StdClass();
                            $i->latitude = 57.0445;
                            $i->longitude = 9.93;
                            $i->date = $start->format('m/d H:i');;
                            $i->active = false;

                        } else {
                            $i = clone $i;
                            $i = $this->buildStep($position, $user, $start);
                        }

                        /*
                        var_dump($user->getId(), $start->format('m/d H:i:s'), $position->getCreatedAt()->format('m/d H:i:s'));
                        var_dump('added, step');
                         */
                        $p->positions[] = $i;

                        $start->add($interval);
                    }

                    /*
                    var_dump($user->getId(), $start->format('m/d H:i:s'), $position->getCreatedAt()->format('m/d H:i:s'));
                    var_dump('added, future');
                     */
                    $p->positions[] = $this->buildStep($position, $user, $start);

                    $start->add($interval);

                    break;
                }

                $prevTicker = clone $start;
                //var_dump('next');
            }

            while ($start <= $end) {
                /*
                var_dump($user->getId(), $start->format('m/d H:i:s'));
                var_dump('added, step');
                */

                $i = clone $i;
                $i->date = $start->format('m/d H:i');
                $i->active = false;
                $i->done = true;

                $p->positions[] = $i;

                $start->add($interval);
            }

            $res[] = $p;
        }

        /*
        foreach ($res as $r) {
            var_dump($r->user, $r->rank);
            foreach ($r->positions as $i) {
                //var_dump($i->date, $r->user, $i->active);
            }
        }
        die('meh');
         */

        return array(
            'users' => json_encode($res),
            'counter' => json_encode($counter)
        );
    }

    private function buildStep(Position $position, User $user, \DateTime $date)
    {
        $i = new \StdClass();
        $i->longitude = $position->getLongitude();
        $i->latitude = $position->getLatitude();
        $i->date = $date->format('m/d H:i');
        $i->active = true;

        return $i;
    }
}
