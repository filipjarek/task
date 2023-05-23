<?php

namespace App\Service;

interface FeeCalculatorInterface
{
    public function calculateFee(float $loanAmount): float;
}