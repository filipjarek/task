<?php

namespace App\Tests\Service;

use App\Service\FeeCalculator;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    public function testIfCalculateFeeWorks()
    {
        $feeStructure = [
            1000 => 50,
            2000 => 90,
            3000 => 90,
            4000 => 115,
            5000 => 100,
            6000 => 120,
            7000 => 140,
            8000 => 160,
            9000 => 180,
            10000 => 200,
            11000 => 220,
            12000 => 240,
            13000 => 260,
            14000 => 280,
            15000 => 300,
            16000 => 320,
            17000 => 340,
            18000 => 360,
            19000 => 380,
            20000 => 400
        ];

        $calculator = new FeeCalculator($feeStructure);

        // Test cases
        $this->assertEquals(50, $calculator->calculateFee(1000));
        $this->assertEquals(60, $calculator->calculateFee(1200));
        $this->assertEquals(90, $calculator->calculateFee(2750));
        $this->assertEquals(100, $calculator->calculateFee(5000));
        $this->assertEquals(130, $calculator->calculateFee(6500));
        $this->assertEquals(140, $calculator->calculateFee(7000));
        $this->assertEquals(200, $calculator->calculateFee(10000));
        $this->assertEquals(300, $calculator->calculateFee(15000));
        $this->assertEquals(385, $calculator->calculateFee(19250));
        $this->assertEquals(400, $calculator->calculateFee(20000));

        // Test invalid loan amounts
        $this->expectException(\InvalidArgumentException::class);
        $calculator->calculateFee(500);
        $calculator->calculateFee(22000);
    }
}
