<?php

namespace Dade\Maps;

class Maps
{
    /**
     * @var array<int,float>
     */
    private array $speed = [];

    /**
     * @var array<int,float>
     */
    private array $incrementalList = [];

    /**
     * @var array<int,float>
     */
    private array $intervalAreas = [];

    /**
     * @var array<int,float>
     */
    private array $localMaxima = [];

    private float $maxAcceleration = 0;
    private float $maxDeceleration = 0;
    private float $maxSpeed = 0;
    private string $previousSign = '=';

    /**
     * Constructor.
     *
     * @param array<int,float> $speed
     */
    public function __construct($speed)
    {
        $this->speed = $speed;

        $this->analyseValues();
    }

    public function areaBelowCurve(int $from_index_a, int $to_index_b): float
    {
        $sum_b = array_sum(array_slice($this->intervalAreas, 0, $to_index_b));

        $sum_a = array_sum(array_slice($this->intervalAreas, 0, $from_index_a));

        return round($sum_b - $sum_a, 2);
    }

    /**
     * @return array<int,float>
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return array<int,float>
     */
    public function getIncrementalList()
    {
        return $this->incrementalList;
    }

    /**
     * @return array<int,float>
     */
    public function getLocalMaxima()
    {
        return $this->localMaxima;
    }

    /**
     * @return array<int,float>
     */
    public function getIntervalAreas()
    {
        return $this->intervalAreas;
    }

    public function getMaxSpeed(): float
    {
        return $this->maxSpeed;
    }

    public function getMaxAcceleration(): float
    {
        return $this->maxAcceleration;
    }

    public function getMaxDeceleration(): float
    {
        return $this->maxDeceleration;
    }

    private function analyseValues(): void
    {
        $arraySize = count($this->speed);

        if ($arraySize < 1) {
            throw new \Exception('You must have at least 1 value');
        }

        $this->maxSpeed = $this->speed[0];

        $delta_s = 1;

        for ($s_index = 0; $s_index < $arraySize - 1; $s_index += $delta_s) {
            $actual_speed = $this->speed[$s_index];

            if ($actual_speed > $this->maxSpeed) {
                $this->maxSpeed = $actual_speed;
            }

            $h = $s_index + $delta_s;

            $delta_speed_s = $this->speed[$h] - $actual_speed;

            $incremental = $delta_speed_s / $delta_s;

            if ($incremental < 0 && '-' != $this->previousSign) {
                $this->localMaxima[] = $actual_speed;
                $this->previousSign = '-';
            } elseif ($incremental > 0 && '+' != $this->previousSign) {
                $this->previousSign = '+';
            }

            if ($incremental > $this->maxAcceleration) {
                $this->maxAcceleration = $incremental;
            }

            if ($incremental < $this->maxDeceleration) {
                $this->maxDeceleration = $incremental;
            }

            $this->incrementalList[] = $incremental;

            if ($delta_speed_s > 0) {
                $baseRectangleHeight = $actual_speed;
            } else {
                $baseRectangleHeight = $this->speed[$h];
            }

            $baseRectangleArea = $delta_s * $baseRectangleHeight;

            $triangleArea = $delta_s * abs($delta_speed_s) / 2;

            $intervalArea = $baseRectangleArea + $triangleArea;

            $this->intervalAreas[] = $intervalArea * 1000 / 3600;
        }
    }
}
