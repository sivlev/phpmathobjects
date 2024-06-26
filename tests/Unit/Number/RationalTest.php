<?php
/*
 * PHPMathObjects Library
 *
 * @see https://github.com/sivlev/PHPMathObjects
 *
 * @author Sergei Ivlev <sergei.ivlev@chemie.uni-marburg.de>
 * @copyright (c) 2024 Sergei Ivlev
 * @license https://opensource.org/license/mit The MIT License
 *
 * @note This software is distributed "as is", with no warranty expressed or implied, and no guarantee for accuracy or applicability to any purpose. See the license text for details.
 */

declare(strict_types=1);

namespace Number;

use PHPMathObjects\Exception\DivisionByZeroException;
use PHPMathObjects\Exception\InvalidArgumentException;
use PHPMathObjects\Number\Rational;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

/**
 * Test case for the Rational class
 */
class RationalTest extends TestCase
{
    // Tolerance used to compare two floats
    protected const e = 1e-8;

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestDox("Rational class constructor creates an instance its class")]
    public function testConstruct(): void
    {
        $r = new Rational(0, 0, 1);
        $this->assertInstanceOf(Rational::class, $r);
    }

    /**
     * @param int $whole
     * @param int $numerator
     * @param int $denominator
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([32, 3, 0])]
    #[TestWith([0, -12, 0])]
    #[TestDox("Rational class constructor throws exception if denominator equals zero")]
    public function testConstructException(int $whole, int $numerator, int $denominator): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Rational($whole, $numerator, $denominator);
    }

    /**
     * @param int $whole
     * @param int $numerator
     * @param int $denominator
     * @param int $expectedWhole
     * @param int $expectedNumerator
     * @param int $expectedDenominator
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([0, 0, 1, 0, 0, 1])]
    #[TestWith([0, 0, 2, 0, 0, 1])]
    #[TestWith([1, 1, 1, 2, 0, 1])]
    #[TestWith([1, 1, 2, 1, 1, 2])]
    #[TestWith([1, 2, 4, 1, 1, 2])]
    #[TestWith([1, 4, 2, 3, 0, 1])]
    #[TestWith([1, 4, 2, 3, 0, 1])]
    #[TestWith([-1, -1, 2, -1, -1, 2])]
    #[TestWith([-1, 1, 2, 0, -1, 2])]
    #[TestWith([1, 1, -2, 0, 1, 2])]
    #[TestWith([5, -3, -4, 5, 3, 4])]
    #[TestWith([10, -36, 4, 1, 0, 1])]
    #[TestWith([1, 8, 6, 2, 1, 3])]
    #[TestWith([-1, 1, -2, -1, -1, 2])]
    #[TestWith([0, 0, -5,  0, 0, 1])]
    #[TestWith([-6, 8, 2, -2, 0, 1])]
    #[TestWith([15, 9, -63, 14, 6, 7])]
    #[TestDox("Rational class getters return the expected whole part, numerator and denominator values")]
    public function testGetters(int $whole, int $numerator, int $denominator, int $expectedWhole, int $expectedNumerator, int $expectedDenominator): void
    {
        $r = new Rational($whole, $numerator, $denominator);
        $this->assertEquals($expectedWhole, $r->whole());
        $this->assertEquals($expectedNumerator, $r->numerator());
        $this->assertEquals($expectedDenominator, $r->denominator());
    }

    /**
     * @param string $string
     * @param int $whole
     * @param int $numerator
     * @param int $denominator
     * @param bool $exception
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["13 3/8", 13, 3, 8])]
    #[TestWith(["2 16/8", 4, 0, 1])]
    #[TestWith(["-1 1/5", -1, -1, 5])]
    #[TestWith(["8/17", 0, 8, 17])]
    #[TestWith(["-6/5", -1, -1, 5])]
    #[TestWith(["-17", -17, 0, 1])]
    #[TestWith(["5", 5, 0, 1])]
    #[TestWith(["0", 0, 0, 1])]
    #[TestWith(["   -76    2/5    ", -76, -2, 5])]
    #[TestWith(["-10 18/16", -11, -1, 8])]
    #[TestWith(["-10 -18/16", -10, -18, 16, true])]
    #[TestWith(["-10s", -10, -18, 16, true])]
    #[TestWith(["-10 18/-16", -10, -18, 16, true])]
    #[TestWith(["--10 -18/16", -10, -18, 16, true])]
    #[TestWith(["-10 -18", -10, -18, 16, true])]
    #[TestDox("FromString() factory creates a correct rational number from a string")]
    public function testFromString(string $string, int $whole, int $numerator, int $denominator, bool $exception = false): void
    {
        if ($exception) {
            $this->expectException(InvalidArgumentException::class);
        }

        $r = Rational::fromString($string);
        $this->assertEquals($whole, $r->whole());
        $this->assertEquals($numerator, $r->numerator());
        $this->assertEquals($denominator, $r->denominator());
    }

    /**
     * @param int $whole
     * @param int $numerator
     * @param int $denominator
     * @param string $string
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([1, 0, 1, "1"])]
    #[TestWith([0, 0, 1, "0"])]
    #[TestWith([0, 1, 1, "1"])]
    #[TestWith([1, 1, 1, "2"])]
    #[TestWith([0, 1, 2, "1/2"])]
    #[TestWith([0, -1, 2, "-1/2"])]
    #[TestWith([0, 1, -2, "-1/2"])]
    #[TestWith([0, -4, 3, "-1 1/3"])]
    #[TestWith([1, 1, 2, "1 1/2"])]
    #[TestWith([1, 2, 4, "1 1/2"])]
    #[TestWith([-1, 1, 2, "-1/2"])]
    #[TestWith([1, -1, 2, "1/2"])]
    #[TestWith([-2, -8, 6, "-3 1/3"])]
    #[TestDox("ToString() method and (string) cast return correct string representation of a rational number")]
    public function testToString(int $whole, int $numerator, int $denominator, string $string): void
    {
        $r = new Rational($whole, $numerator, $denominator);
        $this->assertEquals($string, $r->toString());
        $this->assertEquals($string, (string) $r);
    }

    /**
     * @param string $string
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0"])]
    #[TestWith(["1"])]
    #[TestWith(["-1"])]
    #[TestWith(["1/2"])]
    #[TestWith(["-1/2"])]
    #[TestWith(["1 1/2"])]
    #[TestWith(["-1 1/2"])]
    #[TestDox("FromString() and ToString() method handle normalized strings the same way")]
    public function testToStringExtra(string $string): void
    {
        $r = Rational::fromString($string);
        $this->assertEquals($string, $r->toString());
        $this->assertEquals($string, (string) $r);
    }

    /**
     * @param int $whole
     * @param int $numerator
     * @param int $denominator
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([0, 0, 1])]
    #[TestWith([1, 0, 1])]
    #[TestWith([15, 15, 1])]
    #[TestWith([12, 6, 7])]
    #[TestWith([-10, 5, 10])]
    #[TestWith([-7, -5, 115])]
    #[TestDox("ToFloat() method converts the rational number to a float")]
    public function testToFloat(int $whole, int $numerator, int $denominator): void
    {
        $r = new Rational($whole, $numerator, $denominator);
        $this->assertEqualsWithDelta($whole + $numerator / $denominator, $r->toFloat(), self::e);
    }

    /**
     * @param int $number
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([0])]
    #[TestWith([1])]
    #[TestWith([14])]
    #[TestWith([-6])]
    #[TestWith([-1000])]
    #[TestDox("FromInt() factory method creates a rational number from an integer")]
    public function testFromInt(int $number): void
    {
        $r = Rational::fromInt($number);
        $this->assertEquals($number, $r->whole());
        $this->assertEquals(0, $r->numerator());
        $this->assertEquals(1, $r->denominator());
    }

    /**
     * @param int|float $number
     * @param float $precision
     * @param string $string
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith([0, "0"])]
    #[TestWith([1, "1"])]
    #[TestWith([-1, "-1"])]
    #[TestWith([0.1, "1/10"])]
    #[TestWith([-1.1, "-1 1/10"])]
    #[TestWith([15.3333333, "15 1/3"])]
    #[TestWith([-7.6666666, "-7 2/3"])]
    #[TestWith([0.22543352, "39/173"])]
    #[TestWith([0.22543433, "1622/7195", 1e-4])]
    #[TestWith([0.116116116, "116/999"])]
    #[TestDox("FromFloat() factory method convert a float number into a rational with a given precision")]
    public function testFromFloat(int|float $number, string $string, float $precision = 1e-3): void
    {
        $r = Rational::fromFloat($number, $precision);
        $this->assertEquals($string, $r->toString());
    }

    /**
     * @param string $number
     * @param bool $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", true])]
    #[TestWith(["1", false])]
    #[TestWith(["-1", false])]
    #[TestWith(["-1/2", false])]
    #[TestWith(["1/2", false])]
    #[TestWith(["5 3/8", false])]
    #[TestWith(["-5 3/8", false])]
    #[TestDox("IsZero() method returns true if the rational number equals zero")]
    public function testIsZero(string $number, bool $expected): void
    {
        $r = Rational::fromString($number);
        $this->assertEquals($expected, $r->isZero());
    }

    /**
     * @param string $number
     * @param bool $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", false])]
    #[TestWith(["1", false])]
    #[TestWith(["-1", true])]
    #[TestWith(["-1/2", true])]
    #[TestWith(["1/2", false])]
    #[TestWith(["5 3/8", false])]
    #[TestWith(["-5 3/8", true])]
    #[TestDox("IsNegative() method returns true if the rational number is negative")]
    public function testIsNegative(string $number, bool $expected): void
    {
        $r = Rational::fromString($number);
        $this->assertEquals($expected, $r->isNegative());
    }

    /**
     * @param string $number
     * @param bool $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", false])]
    #[TestWith(["1", true])]
    #[TestWith(["-1", false])]
    #[TestWith(["-1/2", false])]
    #[TestWith(["1/2", true])]
    #[TestWith(["5 3/8", true])]
    #[TestWith(["-5 3/8", false])]
    #[TestDox("IsPositive() method returns true if the rational number is positive")]
    public function testIsPositive(string $number, bool $expected): void
    {
        $r = Rational::fromString($number);
        $this->assertEquals($expected, $r->isPositive());
    }

    /**
     * @param string $number
     * @param bool $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", true])]
    #[TestWith(["1", true])]
    #[TestWith(["-1", true])]
    #[TestWith(["-1/2", false])]
    #[TestWith(["1/2", false])]
    #[TestWith(["5 3/8", false])]
    #[TestWith(["-5 3/8", false])]
    #[TestDox("IsInteger() method returns true if the rational number is integer")]
    public function testIsInteger(string $number, bool $expected): void
    {
        $r = Rational::fromString($number);
        $this->assertEquals($expected, $r->isInteger());
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param bool $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", true])]
    #[TestWith(["0", "1/2", false])]
    #[TestWith(["1/2", "1/2", true])]
    #[TestWith(["-1/2", "1/2", false])]
    #[TestWith(["5 1/3", "5 1/3", true])]
    #[TestWith(["5 1/3", "5 2/3", false])]
    #[TestWith(["5 1/3", "5 1/6", false])]
    #[TestWith(["-5 1/3", "5 1/3", false])]
    #[TestWith(["5", "5", true])]
    #[TestDox("IsEqual() method compares two rational numbers")]
    public function testIsEqual(string $number1, string $number2, bool $expected): void
    {
        $r1 = Rational::fromString($number1);
        $r2 = Rational::fromString($number2);
        $this->assertEquals($expected, $r1->isEqual($r2));
        $this->assertEquals($expected, $r2->isEqual($r1));
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param string $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", "0"])]
    #[TestWith(["1", "1", "2"])]
    #[TestWith(["1", "-1", "0"])]
    #[TestWith(["1/2", "1/2", "1"])]
    #[TestWith(["1/2", "-1/2", "0"])]
    #[TestWith(["1 1/2", "1/2", "2"])]
    #[TestWith(["1 1/2", "-1/2", "1"])]
    #[TestWith(["1 1/2", "-1 1/2", "0"])]
    #[TestWith(["1", "1/3", "1 1/3"])]
    #[TestWith(["3", "-1/3", "2 2/3"])]
    #[TestWith(["1/3", "1/3", "2/3"])]
    #[TestWith(["1/3", "1/2", "5/6"])]
    #[TestWith(["1/3", "-1/4", "1/12"])]
    #[TestWith(["5 6/7", "-3 3/8", "2 27/56"])]
    #[TestDox("Add() method adds one rational number to another")]
    public function testAdd(string $number1, string $number2, string $expected): void
    {
        $r1 = Rational::fromString($number1);
        $r2 = Rational::fromString($number2);
        $this->assertEquals($expected, $r1->add($r2)->toString());
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param string $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", "0"])]
    #[TestWith(["1", "1", "0"])]
    #[TestWith(["1", "-1", "2"])]
    #[TestWith(["1/2", "1/2", "0"])]
    #[TestWith(["1/2", "-1/2", "1"])]
    #[TestWith(["1 1/2", "1/2", "1"])]
    #[TestWith(["1 1/2", "-1/2", "2"])]
    #[TestWith(["1 1/2", "-1 1/2", "3"])]
    #[TestWith(["1", "1/3", "2/3"])]
    #[TestWith(["3", "-1/3", "3 1/3"])]
    #[TestWith(["1/3", "1/3", "0"])]
    #[TestWith(["1/3", "1/2", "-1/6"])]
    #[TestWith(["1/3", "-1/4", "7/12"])]
    #[TestWith(["5 6/7", "-3 3/8", "9 13/56"])]
    #[TestDox("Subtract() method subtracts one rational number from another")]
    public function testSubtract(string $number1, string $number2, string $expected): void
    {
        $r1 = Rational::fromString($number1);
        $r2 = Rational::fromString($number2);
        $this->assertEquals($expected, $r1->subtract($r2)->toString());
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param string $expected
     * @return void
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", "0"])]
    #[TestWith(["1", "1", "1"])]
    #[TestWith(["1", "-1", "-1"])]
    #[TestWith(["1/2", "1/2", "1/4"])]
    #[TestWith(["1/2", "-1/2", "-1/4"])]
    #[TestWith(["1 1/2", "1/2", "3/4"])]
    #[TestWith(["1 1/2", "-1/2", "-3/4"])]
    #[TestWith(["1 1/2", "-1 1/2", "-2 1/4"])]
    #[TestWith(["1", "1/3", "1/3"])]
    #[TestWith(["3", "-1/3", "-1"])]
    #[TestWith(["1/3", "1/3", "1/9"])]
    #[TestWith(["1/3", "1/2", "1/6"])]
    #[TestWith(["1/3", "-1/4", "-1/12"])]
    #[TestWith(["5 6/7", "-3 3/8", "-19 43/56"])]
    #[TestDox("Multiply() method multiplies one rational number by another")]
    public function testMultiply(string $number1, string $number2, string $expected): void
    {
        $r1 = Rational::fromString($number1);
        $r2 = Rational::fromString($number2);
        $this->assertEquals($expected, $r1->multiply($r2)->toString());
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param string $expected
     * @param bool $exception
     * @return void
     * @throws DivisionByZeroException
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", "0", true])]
    #[TestWith(["1", "1", "1"])]
    #[TestWith(["1", "-1", "-1"])]
    #[TestWith(["1/2", "1/2", "1"])]
    #[TestWith(["1/2", "-1/2", "-1"])]
    #[TestWith(["1 1/2", "1/2", "3"])]
    #[TestWith(["1 1/2", "-1/2", "-3"])]
    #[TestWith(["1 1/2", "-1 1/2", "-1"])]
    #[TestWith(["1", "1/3", "3"])]
    #[TestWith(["3", "-1/3", "-9"])]
    #[TestWith(["1/3", "1/3", "1"])]
    #[TestWith(["1/3", "1/2", "2/3"])]
    #[TestWith(["1/3", "-1/4", "-1 1/3"])]
    #[TestWith(["5 6/7", "-3 3/8", "-1 139/189"])]
    #[TestDox("Divide() method divides one rational number by another")]
    public function testDivide(string $number1, string $number2, string $expected, bool $exception = false): void
    {
        if ($exception) {
            $this->expectException(DivisionByZeroException::class);
        }
        $r1 = Rational::fromString($number1);
        $r2 = Rational::fromString($number2);
        $this->assertEquals($expected, $r1->divide($r2)->toString());
    }

    /**
     * @param string $number
     * @param string $expected
     * @param bool $exception
     * @return void
     * @throws DivisionByZeroException
     * @throws InvalidArgumentException
     */
    #[TestWith(["0", "0", true])]
    #[TestWith(["1", "1"])]
    #[TestWith(["2", "1/2"])]
    #[TestWith(["-2", "-1/2"])]
    #[TestWith(["-1/2", "-2"])]
    #[TestWith(["1/2", "2"])]
    #[TestWith(["5 2/3", "3/17"])]
    #[TestWith(["-8/25", "-3 1/8"])]
    #[TestDox("Reciprocal() method returns the multiplicative inverse of the rational")]
    public function testReciprocal(string $number, string $expected, bool $exception = false): void
    {
        if ($exception) {
            $this->expectException(DivisionByZeroException::class);
        }
        $r = Rational::fromString($number);
        $this->assertEquals($expected, $r->reciprocal()->toString());
    }
}
