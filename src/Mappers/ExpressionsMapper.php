<?php

namespace App\Mappers;

use App\Models\Expression;

class ExpressionsMapper
{
    public function map($analyticsRequest): Expression
    {
        $rawExpression = $analyticsRequest->getExpression();
        $parentExpression = new Expression();
        $parentExpression->setFn($rawExpression['fn']);

        if (is_array($rawExpression['a'])) {
            $rawExpressionA = $rawExpression['a'];
            $nestedExpression = new Expression();
            $nestedExpression->setFn($rawExpressionA['fn']);
            $nestedExpression->setA($rawExpressionA['a']);
            $nestedExpression->setB($rawExpressionA['b']);

            $parentExpression->setA($nestedExpression);
        } else {
            $parentExpression->setA($rawExpression['a']);
        }

        if (is_array($rawExpression['b'])) {
            $rawExpressionB = $rawExpression['b'];
            $nestedExpression = new Expression();
            $nestedExpression->setFn($rawExpressionB['fn']);
            $nestedExpression->setA($rawExpressionB['a']);
            $nestedExpression->setB($rawExpressionB['b']);

            $parentExpression->setB($nestedExpression);
        } else {
            $parentExpression->setB($rawExpression['b']);
        }

        return $parentExpression;
    }
}
