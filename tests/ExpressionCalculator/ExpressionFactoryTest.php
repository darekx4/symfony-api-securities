<?php

namespace App\Tests\ExpressionCalculator;

use App\Constants\Operators;
use App\Exceptions\DivisionByZeroErrorException;
use App\Exceptions\NoMatchingOperationException;
use App\ExpressionCalculator\ExpressionFactory;
use PHPUnit\Framework\TestCase;

class ExpressionFactoryTest extends TestCase
{
    public function testAddition()
    {
        $result = ExpressionFactory::calculate(Operators::Add, 12, 13);

        $this->assertEquals($result, 25);
    }

    public function testSubtraction()
    {
        $result = ExpressionFactory::calculate(Operators::Subtract, 12, 13);

        $this->assertEquals($result, -1);
    }

    public function testMultiplication()
    {
        $result = ExpressionFactory::calculate(Operators::Multiply, 12, 13);

        $this->assertEquals($result, 156);
    }

    public function testDivision()
    {
        $result = ExpressionFactory::calculate(Operators::Divide, 12, 13);

        $this->assertEquals($result, 0.92);
    }

    public function testNotExistingOperator()
    {
        $this->expectException(NoMatchingOperationException::class);

        ExpressionFactory::calculate('$#$', 12, 13);

    }

    public function testDivisionByZero()
    {
        $this->expectException(DivisionByZeroErrorException::class);

        ExpressionFactory::calculate(Operators::Divide, 12, 0);

    }
}
