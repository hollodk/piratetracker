<?php

namespace Hollo\TrackerBundle\Helper;

class CoordinateHelper
{
    public function getFirstRoute()
    {
        $route = array();

        $route[] = array('lat' => 57.037972, 'lng' => 9.945928);
        $route[] = array('lat' => 57.038198, 'lng' => 9.948483);
        $route[] = array('lat' => 57.038976, 'lng' => 9.948213);
        $route[] = array('lat' => 57.039236, 'lng' => 9.950825);
        $route[] = array('lat' => 57.040428, 'lng' => 9.950127);
        $route[] = array('lat' => 57.040678, 'lng' => 9.951832);
        $route[] = array('lat' => 57.041536, 'lng' => 9.951243);
        $route[] = array('lat' => 57.043842, 'lng' => 9.949548);
        $route[] = array('lat' => 57.044553, 'lng' => 9.948330);
        $route[] = array('lat' => 57.045277, 'lng' => 9.946417);
        $route[] = array('lat' => 57.046292, 'lng' => 9.946069);

        return $route;
    }

    private function setSecondRoute($map)
    {
    }

    public function getSecondRoute()
    {
        $route = array();

        $route[] = array('lat' => 57.046232, 'lng' => 9.944152);
        $route[] = array('lat' => 57.046667, 'lng' => 9.943945);
        $route[] = array('lat' => 57.046729, 'lng' => 9.942812);
        $route[] = array('lat' => 57.046873, 'lng' => 9.938995);
        $route[] = array('lat' => 57.046925, 'lng' => 9.934474);
        $route[] = array('lat' => 57.046894, 'lng' => 9.932432);
        $route[] = array('lat' => 57.047045, 'lng' => 9.931282);
        $route[] = array('lat' => 57.048372, 'lng' => 9.926516);
        $route[] = array('lat' => 57.048765, 'lng' => 9.926214);
        $route[] = array('lat' => 57.049551, 'lng' => 9.925467);
        $route[] = array('lat' => 57.050230, 'lng' => 9.924189);
        $route[] = array('lat' => 57.051264, 'lng' => 9.920382);
        $route[] = array('lat' => 57.051713, 'lng' => 9.919271);
        $route[] = array('lat' => 57.050984, 'lng' => 9.918633);
        $route[] = array('lat' => 57.051293, 'lng' => 9.916752);
        $route[] = array('lat' => 57.049291, 'lng' => 9.916188);
        $route[] = array('lat' => 57.047500, 'lng' => 9.915102);
        $route[] = array('lat' => 57.046385, 'lng' => 9.913544);
        $route[] = array('lat' => 57.045010, 'lng' => 9.912332);
        $route[] = array('lat' => 57.042575, 'lng' => 9.911002);

        return $route;
    }
}
