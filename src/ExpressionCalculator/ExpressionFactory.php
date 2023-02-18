<?php
namespace App\ExpressionCalculator;

use App\Constant\Generic;
use App\Constant\Operators;
use App\Exception\DivisionByZeroErrorException;
use App\Exception\NoMatchingOperationException;
use App\ExpressionCalculator\Operations\{Add, Divide, Multiply, Subtract};

final class ExpressionFactory
{
    /**
     * @throws DivisionByZeroErrorException
     * @throws NoMatchingOperationException
     */
    public static function calculate(string $operator, int $argA, int $argB): float
    {
        switch ($operator) {
            case Operators::Add:
                $result = new Add();
                $result->calculate($argA, $argB);
                break;
            case Operators::Subtract:
                $result = new Subtract();
                $result->calculate($argA, $argB);
                break;
            case Operators::Multiply:
                $result = new Multiply();
                $result->calculate($argA, $argB);
                break;
            case Operators::Divide:
                $result = new Divide();
                if(!$argB){
                    throw new DivisionByZeroErrorException(Generic::DIVISION_BY_ZERO_ERROR);
                }
                $result->calculate($argA, $argB);
                break;
            default:
                throw new NoMatchingOperationException('Unsupported operator: ' . $operator);
        }

        return round($result->getResult(),Generic::ROUNDING_PRECISION);
    }
}
