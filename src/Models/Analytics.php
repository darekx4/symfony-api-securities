<?php
namespace App\Models;

class Analytics
{
    private $expression;
    private string $security;

    public function getExpression()
    {
        return $this->expression;
    }

    public function setExpression($expression): void
    {
        $this->expression = $expression;
    }

    public function getSecurity(): string
    {
        return $this->security;
    }

    public function setSecurity(string $security): void
    {
        $this->security = $security;
    }
}
