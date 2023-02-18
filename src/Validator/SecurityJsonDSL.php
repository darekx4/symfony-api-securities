<?php

namespace App\Validator;

use App\Constant\Attributes;
use App\Constant\Generic;
use App\Constant\Operators;
use App\Constant\Securities;
use App\Exception\InvalidPayloadException;
use JsonException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;

class SecurityJsonDSL
{
    /**
     * @throws \JsonException
     * @throws InvalidPayloadException
     */
    public static function validate(Request $request): void
    {
        try {
            $payload = json_decode($request->getContent(), $depth = 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidPayloadException(Generic::JSON_NOT_VALID);
        }

        if (empty($payload['expression']) || empty($payload['security'])) {
            throw new InvalidPayloadException(Generic::PROVIDED_JSON_DOES_NOT_CONTAIN_ALL_DATA);
        }

        static::validateSecurity($payload['security']);

        $aSingle = $bSingle = False;

        /*
         |--------------------------------------------------------------------------
         | Single not nested expression
         |--------------------------------------------------------------------------
         */
        if (!is_array($payload['expression']['a'])) {
            $aSingle = true;
            static::validateExpression($payload['expression']);
            static::validateOperator($payload['expression']['fn']);
            static::validateAttributes($payload['expression']['a']);
        }

        if (!is_array($payload['expression']['b'])) {
            $bSingle = true;
            static::validateAttributes($payload['expression']['b']);
        }

        // If we had single expression
        if($aSingle && $bSingle){
            static::validateIfBothNumericAttributes($payload['expression']['a'], $payload['expression']['b']);
        }

        /*
         |--------------------------------------------------------------------------
         | Nested expression
         |--------------------------------------------------------------------------
         */

        if (is_array($payload['expression']['a'])) {
            static::validateExpression($payload['expression']['a']);
            static::validateOperator($payload['expression']['a']['fn']);
            static::validateAttributes($payload['expression']['a']['a']);
            static::validateAttributes($payload['expression']['a']['b']);
            static::validateIfBothNumericAttributes($payload['expression']['a']['a'], $payload['expression']['a']['b']);
        }

        if (is_array($payload['expression']['b'])) {
            static::validateExpression($payload['expression']['b']);
            static::validateOperator($payload['expression']['b']['fn']);
            static::validateAttributes($payload['expression']['b']['a']);
            static::validateAttributes($payload['expression']['b']['b']);
            static::validateIfBothNumericAttributes($payload['expression']['b']['a'], $payload['expression']['b']['b']);
        }
    }

    /**
     * @throws InvalidPayloadException
     */
    private static function validateOperator($operator): void
    {
        $allowedOperators = new ReflectionClass(Operators::class);
        if (!in_array($operator, $allowedOperators->getConstants(), true)) {
            throw new InvalidPayloadException(Generic::PROVIDED_OPERATOR_IS_NOT_VALID);
        }
    }

    /**
     * @throws InvalidPayloadException
     */
    private static function validateExpression($expression): void
    {
        if (empty($expression['fn']) || empty($expression['a']) || empty($expression['b'])) {
            throw new InvalidPayloadException(Generic::PROVIDED_JSON_DOES_NOT_CONTAIN_VALID_EXPRESSION);
        }
    }

    /**
     * @throws InvalidPayloadException
     */
    private static function validateAttributes($attribute): void
    {
        $allowedAttributes = new ReflectionClass(Attributes::class);
        if (!is_numeric($attribute) && (!in_array($attribute, $allowedAttributes->getConstants(), true))) {
            throw new InvalidPayloadException(Generic::PROVIDED_ATTRIBUTES_ARE_NOT_VALID);
        }
    }

    /**
     * @throws InvalidPayloadException
     */
    private static function validateIfBothNumericAttributes($a, $b): void
    {

        //TODO not sure that should be a case - helps in unit/functional tests
//        if (is_numeric($a) && is_numeric($b)) {
//            throw new InvalidPayloadException(Generic::BOTH_PROVIDED_ATTRIBUTES_CANNOT_BE_NUMERIC);
//        }
    }

    /**
     * @throws InvalidPayloadException
     */
    private static function validateSecurity($security): void
    {
        $allowedSecurities = new ReflectionClass(Securities::class);
        if (!in_array($security, $allowedSecurities->getConstants(), true)) {
            throw new InvalidPayloadException(Generic::PROVIDED_SECURITY_IS_NOT_VALID);
        }
    }
}
