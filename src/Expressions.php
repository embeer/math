<?php

namespace embeer\classes;

class Expressions
{
    /**
     * @var bool $probabilityOfNegative - probability (range 0-100 %) of generating negative number
     */
    private $probabilityOfNegative = 50;

    /**
     * @var int $decialPlaces - allowed decimal places (0 stands for integers). Cannot exceed 6 places
     */
    private $decimalPlaces = 0;

    /**
     * @var bool $probabilityOfNonInteger - probability (range 0-100 %) of generating non integer number
     */
    private $probabilityOfNonInteger = 50;

    /**
     * @var bool $probabilityOfFractional - probability (range 0-100 %) of generating non decimal fractional
     */
    private $probabilityOfFractional = 50;

    /**
     * @var bool $probabilityOfProperFractional - probability (range 0-100 %) of generating proper fractional
     */
    private $probabilityOfProperFractional = 75;

    /**
     * @var int $minParenthesisNumber - minimal parenthesis nesting level (not used yet)
     */
    private $minParenthesisNumber = 0;

    /**
     * @var int $maxParenthesisNumber - maximal parenthesis nesting level (not used yet)
     */
    private $maxParenthesisNumber = 0;

    /**
     * @var bool $probabilityOfOpeningParenthesis - probability (range 0-100 %) of generating opening parenthesis
     */
    private $openingParenthesisProbability = 5;

    /**
     * @var bool $probabilityOfOpeningParenthesis - probability (range 0-100 %) of generating closing parenthesis
     */
    private $closingParenthesisProbability = 10;

    /**
     * @var int $parenthesisNumber - maximum number of parenthesis nesting level
     */
    private $parenthesisNumber = 0;

    /**
     * @var int $parenthesisCount - current parenthesis nesting level count
     */
    private $parenthesisCount = 0;

    /**
     * @var int $numberOfExpressions - number of generated expressions
     */
    private $numberOfExpressions = 10;

    /**
     * @var int $min - module of minimal value used. Cannot be less than 1
     */
    private $minimum = 1;

    /**
     * @var int $max - module of maximal value used. Cannot exceed 2100
     */
    private $maximum = 10;

    /**
     * @var bool $withResult - true if result is printed along with the expression (not used yet)
     */
    private $withResult = false;

    /**
     * @var int $minOperatorsInExpression - minimal number of operators used in an expression
     */
    private $minOperatorsInExpression = 2;

    /**
     * @var int $maxOperatorsInExpression - maximal number of operators used in an expression
     */
    private $maxOperatorsInExpression = 10;

    /**
     * @var int $currentOperatorsCount - current number of generated operators
     */
    private $currentOperatorsCount = 0;

    /**
     * @var array $allowedOperators - array of operators. Operator probability is defined as value for key => value pair
     */
    private $allowedOperators = [
        '+' => 35,
        '-' => 30,
        '*' => 25,
        '/' => 10,
    ];

    /**
     * @var array $operatorsTable - table used during expressions generation. Length 100. Each operator takes as many places in it, as it's
     * probability says. Constructed on getExpressions method entry.
     */
    private $operatorsTable = [];

    public function __construct(array $attributes = null)
    {
        if (isset($attributes)) {
            foreach ($attributes as $key => $value) {
                if (isset($this->$key)) {
                    if (is_array($value)) {
                        $operators = [];
                        foreach ($value as $key => $item) {
                            $operators[$key] = (int)$item;
                            $value           = $operators;
                        }
                    } else {
                        $value = (int)$value;
                    }
                    $this->$key = $value;
                }
            }
        }
    }

    public function setVariables(array $variables)
    {
        foreach ($variables as $name => $value) {
            if (isset($this->$name)) {
                $this->$name = $value;
            }
        }
    }

    public function getVariables($names): array
    {
        $retArray = [];

        if (!is_array($names)) {
            $names = [$names];
        }

        foreach ($names as $name) {
            if (isset($this->$name)) {
                $retArray[$name] = $this->$name;
            }
        }
        return $retArray;
    }

    public function getExpressions(): array
    {
        $expressions = [];

        $this->operatorsTable = [];
        foreach ($this->allowedOperators as $operator => $probability) {
            for ($nLoop = 0; $nLoop < $probability; $nLoop++) {
                $this->operatorsTable[] = $operator;
            }
        }

        for ($nLoop = 0; $nLoop < $this->numberOfExpressions; $nLoop++) {
            $expressions[] = $this->getExpression() . ' = ';
        }
        return $expressions;
    }

    private function getExpression($numberOfOperators = null, $closingSymbol = '')
    {
        $expression  = '';
        $gotOperator = false;

        if ($numberOfOperators === null) {
            $numberOfOperators           = mt_rand($this->minOperatorsInExpression, $this->maxOperatorsInExpression);
            $this->parenthesisCount      = 0;
            $this->currentOperatorsCount = 0;
        } else {
            $this->parenthesisCount++;
        }
        while ($this->currentOperatorsCount < $numberOfOperators) {

            if (($this->isTrue($this->openingParenthesisProbability)) && ($this->parenthesisCount < $this->parenthesisNumber)) {
                $expression = $expression . '(' . $this->getExpression($numberOfOperators, ')');
            } else {
                $expression .= $this->getNumber();
            }
            $breakReported = false;
            if (($this->parenthesisCount > 0) && $gotOperator && ($this->isTrue($this->closingParenthesisProbability))) {
                $this->parenthesisCount--;
                $breakReported = true;
                break;
            }
            $this->currentOperatorsCount++;
            $expression  .= $this->getOperator();
            $gotOperator = true;
        }
        if (!$breakReported) {
            $expression .= $this->getNumber();
        }
        return $expression . $closingSymbol;
    }

    private function getNumber()
    {
        $min = $this->minimum;
        $max = $this->maximum;

        // is it non integer?
        if ($this->isTrue($this->probabilityOfNonInteger)) {
            if ($this->isTrue($this->probabilityOfFractional)) {
                // fractional
                $number = $this->getFractionalNumber($min, $max);
            } else {
                // decimal
                $multiplier = 10 ** $this->decimalPlaces;
                $min        *= $multiplier;
                $max        *= $multiplier;
                $number     = mt_rand($min, $max) / $multiplier;
            }
        } else {
            // integer
            $number = mt_rand($min, $max);
        }
        if ($this->isTrue($this->probabilityOfNegative)) {
            $number = '(-' . $number . ')';
        }

        return $number;
    }

    private function getFractionalNumber($min, $max)
    {
        $numerator   = mt_rand($min, $max);
        $denominator = mt_rand($min, $max);
        $integerPart = 0;
        $number      = '';
        if ($this->isTrue($this->probabilityOfProperFractional)) {
            $integerPart = (integer)($numerator / $denominator);
            $numerator   %= $denominator;
            if ($integerPart !== 0) {
                $number = $integerPart;
            }
        }
        if ($numerator !== 0) {
            $number .= '<sup>' . $numerator . '</sup>&frasl;<sub>' . $denominator . '</sub>';
        }
        return $number;
    }

    private function isTrue($probability)
    {
        return mt_rand(1, 100) <= $probability;
    }

    private function getOperator()
    {
        $operator = $this->operatorsTable[mt_rand(0, 99)];

        return ' ' . (($operator === '/') ? ':' : $operator) . ' ';
    }
}
