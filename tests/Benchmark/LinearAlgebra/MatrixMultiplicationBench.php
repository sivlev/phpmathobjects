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

namespace PHPMathObjects\Tests\Benchmark\LinearAlgebra;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PHPMathObjects\Exception\InvalidArgumentException;
use PHPMathObjects\Exception\MatrixException;
use PHPMathObjects\Exception\OutOfBoundsException;
use PHPMathObjects\LinearAlgebra\Matrix;

/**
 * Benchmark for matrix multiplication with multiply() and mMultiply() methods
 */
#[Groups(["LinearAlgebra"])]
#[BeforeMethods('setUp')]
class MatrixMultiplicationBench
{
    /**
     * @var Matrix A
     */

    protected Matrix $a;

    /**
     * @var Matrix B
     */
    protected Matrix $b;

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function setUp(): void
    {
        $this->a = Matrix::fill(100, 100, 5.5);
        $this->b = Matrix::fill(100, 100, -16.77);
    }

    /**
     * @return void
     * @throws MatrixException
     * @throws InvalidArgumentException
     */
    #[Iterations(5)]
    #[Revs(10)]
    public function benchMultiply(): void
    {
        $this->a->multiply($this->b);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws MatrixException
     */
    #[Iterations(5)]
    #[Revs(10)]
    public function benchMMultiply(): void
    {
        $this->a->mMultiply($this->b);
    }
}
