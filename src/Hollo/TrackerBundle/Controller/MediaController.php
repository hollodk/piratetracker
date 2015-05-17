<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\User;

/**
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * @Route("/{id}/{size}")
     */
    public function indexAction(Request $request, User $entity, $size)
    {
        $im = imagecreatefromstring(base64_decode($entity->getProfileImage()));

        if ($im !== false) {
            $ng = imagescale($im, $size, $size);

            header('Content-Type: image/png');
            imagepng($ng);

            imagedestroy($im);
            imagedestroy($ng);
        }
        else {
            echo 'An error occurred.';
        }
        die();
    }
}
