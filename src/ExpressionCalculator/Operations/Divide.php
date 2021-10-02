<?php
namespace App\ExpressionCalculator\Operations;

class Divide extends Calculate
{
    protected function runCalculation(int $a, int $b): float
    {
        return $a / $b;
    }
}
