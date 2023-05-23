<?php

namespace App\Service;

class FeeCalculator implements FeeCalculatorInterface
{
    private $feeStructure;

    public function __construct(array $feeStructure)
    {
        $this->feeStructure = $feeStructure;
    }

    public function calculateFee(float $loanAmount): float
    {
        if ($loanAmount < 1000 || $loanAmount > 20000) {
            throw new \InvalidArgumentException('Loan amount should be between 1,000 and 20,000 PLN.');
        }

        $breakpoints = array_keys($this->feeStructure);
        sort($breakpoints);

        $lowerBound = $breakpoints[0];
        $upperBound = end($breakpoints);

        if ($loanAmount <= $lowerBound) {
            return $this->feeStructure[$lowerBound];
        }

        if ($loanAmount >= $upperBound) {
            return $this->feeStructure[$upperBound];
        }

        foreach ($breakpoints as $i => $breakpoint) {
            if ($loanAmount > $breakpoint && $loanAmount <= $breakpoints[$i + 1]) {
                $lowerBound = $breakpoint;
                $upperBound = $breakpoints[$i + 1];
                break;
            }
        }

        $lowerFee = $this->feeStructure[$lowerBound];
        $upperFee = $this->feeStructure[$upperBound];

        $feeRange = $upperBound - $lowerBound;
        $loanRange = $loanAmount - $lowerBound;

        $fee = $lowerFee + (($loanRange / $feeRange) * ($upperFee - $lowerFee));

        $totalAmount = $loanAmount + $fee;
        $roundedTotalAmount = ceil($totalAmount);
        $roundedFee = $roundedTotalAmount - $loanAmount;

        $remainder = $roundedTotalAmount % 5;
        if ($remainder != 0) {
            $roundedFee += 5 - $remainder;
        }

        return $roundedFee;
    }
}