<?php
namespace App\ExpressionCalculator\Operations;

class Multiply extends Calculate
{
    protected function runCalculation(int $a, int $b): float
    {
        return $a * $b;
    }
}
