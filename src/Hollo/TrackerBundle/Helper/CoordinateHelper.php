<?php

namespace Hollo\TrackerBundle\Helper;

class CoordinateHelper
{
    public function getFirstRoute()
    {
        $route = array();

        $route[] = array('lat' => 57.037972, 'lon' => 9.945928);
        $route[] = array('lat' => 57.038198, 'lon' => 9.948483);
        $route[] = array('lat' => 57.038976, 'lon' => 9.948213);
        $route[] = array('lat' => 57.039236, 'lon' => 9.950825);
        $route[] = array('lat' => 57.040428, 'lon' => 9.950127);
        $route[] = array('lat' => 57.040678, 'lon' => 9.951832);
        $route[] = array('lat' => 57.041536, 'lon' => 9.951243);
        $route[] = array('lat' => 57.043842, 'lon' => 9.949548);
        $route[] = array('lat' => 57.044553, 'lon' => 9.948330);
        $route[] = array('lat' => 57.045277, 'lon' => 9.946417);
        $route[] = array('lat' => 57.046292, 'lon' => 9.946069);

        return $route;
    }

    private function setSecondRoute($map)
    {
    }

    public function getSecondRoute()
    {
        $route = array();

        $route[] = array('lat' => 57.046232, 'lon' => 9.944152);
        $route[] = array('lat' => 57.046667, 'lon' => 9.943945);
        $route[] = array('lat' => 57.046729, 'lon' => 9.942812);
        $route[] = array('lat' => 57.046873, 'lon' => 9.938995);
        $route[] = array('lat' => 57.046925, 'lon' => 9.934474);
        $route[] = array('lat' => 57.046894, 'lon' => 9.932432);
        $route[] = array('lat' => 57.047045, 'lon' => 9.931282);
        $route[] = array('lat' => 57.048372, 'lon' => 9.926516);
        $route[] = array('lat' => 57.048765, 'lon' => 9.926214);
        $route[] = array('lat' => 57.049551, 'lon' => 9.925467);
        $route[] = array('lat' => 57.050230, 'lon' => 9.924189);
        $route[] = array('lat' => 57.051264, 'lon' => 9.920382);
        $route[] = array('lat' => 57.051713, 'lon' => 9.919271);
        $route[] = array('lat' => 57.050984, 'lon' => 9.918633);
        $route[] = array('lat' => 57.051293, 'lon' => 9.916752);
        $route[] = array('lat' => 57.049291, 'lon' => 9.916188);
        $route[] = array('lat' => 57.047500, 'lon' => 9.915102);
        $route[] = array('lat' => 57.046385, 'lon' => 9.913544);
        $route[] = array('lat' => 57.045010, 'lon' => 9.912332);
        $route[] = array('lat' => 57.042575, 'lon' => 9.911002);

        return $route;
    }
}
