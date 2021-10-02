<?php
namespace App\ExpressionCalculator\Operations;

class Subtract extends Calculate
{
    protected function runCalculation(int $a, int $b): float
    {
        return $a - $b;
    }
}
