<?php

namespace App\Model;

class Expression
{
    private string $fn;
    private $a;
    private $b;

    public function getFn(): string
    {
        return $this->fn;
    }

    public function setFn(string $fn): void
    {
        $this->fn = $fn;
    }

    public function getA()
    {
        return $this->a;
    }

    public function setA($a): void
    {
        $this->a = $a;
    }

    public function getB()
    {
        return $this->b;
    }

    public function setB($b): void
    {
        $this->b = $b;
    }
}
