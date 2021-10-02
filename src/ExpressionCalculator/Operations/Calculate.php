<?php
namespace App\ExpressionCalculator\Operations;

abstract class Calculate
{
    private float $result;

    final public function calculate(int $a, int $b): void
    {
        $this->result = $this->runCalculation($a, $b);
    }

    abstract protected function runCalculation(int $a, int $b): float;

    public function getResult(): float
    {
        return $this->result;
    }
}
