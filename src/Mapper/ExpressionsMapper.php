<?php

namespace App\Mapper;

use App\Model\Expression;

class ExpressionsMapper
{
    public function map($analyticsRequest): Expression
    {
        $rawExpression = $analyticsRequest->getExpression();
        $expression = new Expression();
        $expression->setFn($rawExpression['fn']);

        if (is_array($rawExpression['a'])) {
            $expression->setA($this->createExpression($rawExpression['a']));
        } else {
            $expression->setA($rawExpression['a']);
        }

        if (is_array($rawExpression['b'])) {
            $expression->setB($this->createExpression($rawExpression['b']));
        } else {
            $expression->setB($rawExpression['b']);
        }

        return $expression;
    }

    private function createExpression(array $rawExpression): Expression
    {
        $expression = new Expression();
        $expression->setFn($rawExpression['fn']);

        if (is_array($rawExpression['a'])) {
            $nestedExpressionA = $this->createExpression($rawExpression['a']);
            $expression->setA($nestedExpressionA);
        } else {
            $expression->setA($rawExpression['a']);
        }

        if (is_array($rawExpression['b'])) {
            $nestedExpressionB = $this->createExpression($rawExpression['b']);
            $expression->setB($nestedExpressionB);
        } else {
            $expression->setB($rawExpression['b']);
        }

        return $expression;
    }
}
