<?php
namespace App\Constant;

class Generic
{
    public const ROUNDING_PRECISION = 2;

    /*
     |--------------------------------------------------------------------------
     | OK CONSTANTS
     |--------------------------------------------------------------------------
     */
    public const ASSET_HAS_BEEN_VALUED = 'Asset has been valued';
    /*
    |--------------------------------------------------------------------------
    | ERROR CONSTANTS
    |--------------------------------------------------------------------------
    */
    public const JSON_NOT_VALID = 'JSO not valid';
    public const PROVIDED_JSON_DOES_NOT_CONTAIN_ALL_DATA = 'Provided JSON does not contain all data';
    public const PROVIDED_JSON_DOES_NOT_CONTAIN_VALID_EXPRESSION = 'Provided JSON does not contain valid expression';
    public const PROVIDED_ATTRIBUTES_ARE_NOT_VALID = 'Provided attributes are not valid';
    public const PROVIDED_OPERATOR_IS_NOT_VALID = 'Provided operator fn is not valid';
    public const PROVIDED_SECURITY_IS_NOT_VALID = 'Provided security is not valid';
    public const BOTH_PROVIDED_ATTRIBUTES_CANNOT_BE_NUMERIC = 'Both of the provided attributes a and b cannot be numeric';
    public const DIVISION_BY_ZERO_ERROR = 'Division by zero error - second argument was either null or second expression was null and was used to divide by';
    public const COULD_NOT_DESERIALIZE_REQUEST = 'Could not deserialize request';
}
