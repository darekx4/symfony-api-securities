<?php

namespace App\Service;

use App\Entity\Attribute;
use App\Entity\Facts;
use App\Entity\Security;
use App\Exceptions\DivisionByZeroErrorException;
use App\ExpressionCalculator\ExpressionFactory;
use App\Models\Expression;
use Doctrine\ORM\EntityManagerInterface;

class AssetValuation
{

    private EntityManagerInterface $em;
    private string $asset;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws DivisionByZeroErrorException
     */
    public function calculate(Expression $expression, string $asset)
    {
        $this->asset = $asset;

        $aIsExpression = ($expression->getA() instanceof Expression);
        $bIsExpression = ($expression->getB() instanceof Expression);

        // First option both a and b simple values
        if(!$aIsExpression && !$bIsExpression){
            return ExpressionFactory::calculate($expression->getFn(), $this->getValueA($expression), $this->getValueB($expression));
        }

        // Second option both are instances of Expression
        if($aIsExpression && $bIsExpression){
            $valueA = ExpressionFactory::calculate($expression->getA()->getFn(), $this->getValueA($expression->getA()), $this->getValueB($expression->getA()));
            $valueB = ExpressionFactory::calculate($expression->getB()->getFn(), $this->getValueA($expression->getB()), $this->getValueB($expression->getB()));

            return ExpressionFactory::calculate($expression->getFn(), $valueA, $valueB);
        }

        // Third option - first instance of Expression second simple value
        if($aIsExpression && !$bIsExpression){
            $valueA = ExpressionFactory::calculate($expression->getA()->getFn(), $this->getValueA($expression->getA()), $this->getValueB($expression->getA()));

            return ExpressionFactory::calculate($expression->getFn(), $valueA, $this->getValueB($expression));
        }

        // Fourth option - first instance simple second instance of Expression
        if(!$aIsExpression && $bIsExpression){
            $valueB = ExpressionFactory::calculate($expression->getB()->getFn(), $this->getValueA($expression->getB()), $this->getValueB($expression->getB()));

            return ExpressionFactory::calculate($expression->getFn(), $this->getValueA($expression), $valueB);
        }
    }

   //TODO this should not live here tbh
    private function getValueA($expression){
        $security = $this->em
            ->getRepository(Security::class)
            ->findOneBy(['symbol' => $this->asset]);

        //If it was numeric no need to quest DB
        if (is_numeric($expression->getA())) {
            $a = $expression->getA();
        } else {
            $attributeA = $this->em
                ->getRepository(Attribute::class)
                ->findOneBy(['name' => $expression->getA()]);

            $factsA = $this->em
                ->getRepository(Facts::class)
                ->findOneBy(
                    [
                        'security' => $security,
                        'attribute' => $attributeA
                    ]
                );

            $a = $factsA->getValue();
        }
        return $a;
    }

    //TODO this should not live here tbh
    private function getValueB($expression){
        $security = $this->em
            ->getRepository(Security::class)
            ->findOneBy(['symbol' => $this->asset]);

        //If it was numeric no need to quest DB
        if (is_numeric($expression->getB())) {
            $b = $expression->getB();
        } else {
            $attributeB = $this->em
                ->getRepository(Attribute::class)
                ->findOneBy(['name' => $expression->getB()]);

            $factsB = $this->em
                ->getRepository(Facts::class)
                ->findOneBy(
                    [
                        'security' => $security,
                        'attribute' => $attributeB
                    ]
                );

            $b = $factsB->getValue();
        }

        return $b;
    }

}
