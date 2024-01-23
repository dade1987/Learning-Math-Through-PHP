<?php

require_once 'vendor/autoload.php';

use Dade\Maps\Maps;

$speed = [0, 20, 30, 34, 40, 40, 40, 40, 30, 10, 20, 30, 30, 32, 31, 30, 20, 10, 20, 40, 70, 73, 73, 73, 40, 20, 0];

$analyzer = new Maps($speed);

echo 'Speeds (km/h): '.implode(', ', $analyzer->getSpeed()).'<br><br>';
echo 'Incrementals (km/h per second): '.implode(', ', $analyzer->getIncrementalList()).'<br><br>';
echo 'Local Maxima (km/h): '.implode(', ', $analyzer->getLocalMaxima()).'<br><br>';
echo 'Interval Areas (meters covered in interval): '.implode(', ', $analyzer->getIntervalAreas()).'<br><br>';
echo 'Max Speed (km/h): '.$analyzer->getMaxSpeed().'<br>';
echo 'Max Acceleration: '.$analyzer->getMaxAcceleration().' km/h per second<br>';
echo 'Max Deceleration: '.$analyzer->getMaxDeceleration().' km/h per second<br>';
echo 'Area below index 2 and 5 (meters covered in 3 seconds from 8.8... to the first 11.1... in 2 decimals): '.$analyzer->areaBelowCurve(2, 5).'<br>';
