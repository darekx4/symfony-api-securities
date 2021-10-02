<?php
namespace App\ExpressionCalculator\Operations;

class Add extends Calculate
{
    protected function runCalculation(int $a, int $b): float
    {
        return $a + $b;
    }
}
