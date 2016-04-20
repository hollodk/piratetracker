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
        $start = new \DateTime('2015-05-23 07:00:00');
        $end = new \DateTime('2015-05-24 05:00:00');

        while ($start <= $end) {
            $counter[] = $start->format('m/d H:i');

            $start->add($interval);
        }

        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();

        foreach ($users as $user) {
            $start = new \DateTime('2015-05-23 07:00:00');
            $end = new \DateTime('2015-05-24 05:00:00');
            $prevTicker = clone $start;

            $p = new \StdClass();
            $p->user = $user;
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
                    $p->positions[] = $this->buildStep($position, $start);

                    /*
                    var_dump($user->getId(), $start->format('m/d H:i:s'), $position->getCreatedAt()->format('m/d H:i:s'));
                    var_dump('added, past');
                     */
                    $start->add($interval);

                    break;

                case ($position->getCreatedAt() > $start):
                    while ($position->getCreatedAt() > $start) {

                        $d = clone $start;

                        if (!isset($i)) {
                            $i = new \StdClass();
                            $i->latitude = 57.0445;
                            $i->longitude = 9.93;
                            $i->date = $d->format('m/d H:i');;
                            $i->user = $position->getUser()->getId();
                        } else {
                            $i = $this->buildStep($position, $start);
                            //$i->date = $d->format('m/d H:i');
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
                    $p->positions[] = $this->buildStep($position, $start);

                    $start->add($interval);

                    break;
                }

                $prevTicker = clone $start;
                //var_dump('next');
            }

            if ($start !== $end) {
                while ($end >= $start) {
                    /*
                    var_dump($user->getId(), $start->format('m/d H:i:s'));
                    var_dump('added, step');
                     */

                    $d = clone $start;
                    $i->date = $d->format('m/d H:i');;

                    $p->positions[] = $i;

                    $start->add($interval);
                }
            }

            $res[] = $p;
        }

        /*
        foreach ($res as $r) {
            var_dump(count($r->positions));
            continue;
            foreach ($r->positions as $i) {
                var_dump($i->date, $i->user);
            }
        }
        die('meh');
         */

        return array(
            'users' => $res,
            'counter' => $counter
        );
    }

    private function buildStep(Position $position, \DateTime $date)
    {
        $d = clone $date;

        $i = new \StdClass();
        $i->longitude = $position->getLongitude();
        $i->latitude = $position->getLatitude();
        $i->date = $d->format('m/d H:i');
        $i->user = $position->getUser()->getId();

        return $i;
    }
}
