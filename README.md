Coding Challenge - Symfony API
=====
[![License: MIT](https://img.shields.io/badge/License-MIT-limegreen.svg)](https://opensource.org/licenses/MIT)
[![Fee Calculation](https://github.com/darekx4/symfony-api-securities/actions/workflows/symfony.yml/badge.svg)](https://github.com/darekx4/symfony-api-securities/actions/workflows/symfony.yml)

# Abstract

Symfony API JSON based DSL securities analytics

The goal of the project is to build a small interpreter for a JSON
based DSL that performs some simple analytics on a data set.

This should be exposed as a Symfony-based API that accepts the queries.

## The Data

The data set comprises 3 small csv files, `securities.csv`,
`attributes.csv` and `facts.csv`. There are 10 securities and 10 attributes, and for each
security and each attribute, there is one fact. I.e. there is a one-to-many relationship between
securities and facts, and attributes and facts.

### Securities Schema

| column | type    |
|--------|---------|
| id     | integer |
| symbol | string  |

### Attributes Schema

| column | type    |
|--------|---------|
| id     | integer |
| name   | string  |

### Facts Schema

| column      | type    |
|-------------|---------|
| securityId  | integer |
| attributeId | integer |
| value       | float   |


## The DSL
A query in our DSL has the basic format:
```
{
  "security": <String>,
  "expression": <Expression>
}
```

The security property contains a single string, which is the symbol of a security the user wishes
to evaluate an expression for.

The expression field contains a single expression, which the user wishes to evaluate for the
chosen security. An expression contains an operator (the `fn` property), and the arguments to
that operator has other properties. The arguments to operators can either be the name of an
attribute, a number, or another expression.

You only have to implement one operator, though it should be clear in your solution
how it might be possible to extend the interpreter to include additional operators. Please choose
one of the following operators to implement:

| Operator | Arguments | Behaviour          |
|----------|-----------|--------------------|
| +        | a, b      | Adds a and b       |
| -        | a, b      | Subtracts b from a |
| *        | a, b      | Multiplies a and b |
| /        | a, b      | Divides a by b     |

Here are some example queries, demonstrating what each operator looks like and what the different
parameters can be:

This one uses the `*` operator and makes use of one attribute name and an integer as its arguments:
```
{
  "expression": {"fn": "*", "a": "sales", "b": 2},
  "security": "BBIG"
}
```

This one uses the `/` operator and makes uses two attribute names as arguments:
```
{
  "expression": {"fn": "/", "a": "price", "b": "eps"},
  "security": "HYDR"
}
```

This one uses the `-` operator and the arguments are two expressions, which in turn use the `-`
operator and attribute names as arguments:
```
{
  "expression": {
    "fn": "-", 
    "a": {"fn": "-", "a": "eps", "b": "shares"}, 
    "b": {"fn": "-", "a": "assets", "b": "liabilities"}
  },
  "security": "SHIPW"
}
```

# Implementation

## Requirements
```
>= PHP 8.2, mqsql or different database server
```
## Installation
```
1. Run composer install

2. Inside the .env file place reference to your database eg.
DATABASE_URL=mysql://your_username:your_password@127.0.0.1/your_database_name?serverVersion=8

```

## Populating data in database
```
Create database 
php bin/console make:migration

Create tables
php bin/console doctrine:migrations:migrate

Push data
php bin/console doctrine:fixtures:load
```
![Alt Text](readme/images/2.png)

## Unit tests
```
composer test
```

## Testing the build

```
Make sure php server is running eg.
php -S localhost:8000 -t public/
[Sat Feb 18 17:24:29 2023] PHP 8.2.3 Development Server (http://localhost:8000) started

```
Use browser or REST client - GET request (e.g. Postman) to test your build
```
http://localhost:8000/api/securities/test

You should see following response:
{"error":false,"status":"Asset has been valued","valuation_result":"That was just test"}
```

![Alt Text](readme/images/1.png)

## Using the interpreter

In order to use the interpreter push POST request using REST client (e.g. Postman)

```
http://localhost:8000/api/securities/analytics

Examples of the payloads:

{
    "expression": {"fn": "*", "a": "sales", "b": 2},
    "security": "BBIG"
}

---------------------------------------------------------------

{
    "expression": {"fn": "/", "a": "price", "b": "eps"},
    "security": "HYDR"
}
---------------------------------------------------------------
{
  "expression": {
    "fn": "-", 
    "a": {"fn": "-", "a": "eps", "b": "shares"}, 
    "b": {"fn": "-", "a": "assets", "b": "liabilities"}
  },
  "security": "SHIPW"
}

```

![Alt Text](readme/images/3.png)

More examples of possible payloads can be found in integration tests, If you want torun them locally you need to run server and comment out the line
```
$this->markTestSkipped('Skipping the tests which require running server');
```

## How interpreter calculates valuations?

Let's say we want to value how much would be to multiply security AEHL by 2 using sales value

Table security holds following data:

![Alt Text](readme/images/6.png)

Table attribute contains all possible attributes

![Alt Text](readme/images/4.png)

Table facts contains relations between securities, attributes and values

![Alt Text](readme/images/5.png)


Therefore, in order to calculate AEHL * 2 using sales value

we use payload
```
{
  "expression": {"fn": "*", "a": "sales", "b": 2},
  "security": "AEHL"
}

We are getting

{
    "error": false,
    "status": "Asset has been valued",
    "valuation_result": 8
}

Table facts row with id 4 stores value for security id 1 (AEHL) with attribute id 4 (sales) and value 4 

```