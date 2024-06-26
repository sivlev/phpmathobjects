# PHPMathObjects - A PHP library to handle mathematical objects

![Tests](https://github.com/sivlev/PHPMathObjects/actions/workflows/tests.yml/badge.svg) ![GitHub License](https://img.shields.io/github/license/sivlev/PHPMathObjects) [![Coverage Status](https://coveralls.io/repos/github/sivlev/phpmathobjects/badge.svg?branch=main)](https://coveralls.io/github/sivlev/phpmathobjects?branch=main)

The PHPMathObjects library was created with crystallographic applications in mind but should be suitable for broad variety of projects. 
The library has 100 % coverage with unit tests and is performance-optimized. 
Being actively developed, it is not yet suitable for production environment since the current API is subject to change.

## Installation

Install PHPMathObjects using [Composer](https://getcomposer.org):
```sh
composer require sivlev/phpmathobjects
```
or include the following line to your `composer.json` file:
```json
"sivlev/phpmathobjects": "*"
```

### Requirements

The library requires PHP 8.2 or above. No other external dependencies are required.

## How to use

This section contains simplified lists of PHPMathObjects API methods. Some parameters with default values are omitted for clarity.
For full API reference please refer to ```docs```.

### Contents

 * General Mathematics
   - [Math](#math)
   
 * Linear Algebra
   - [Matrix](#matrix)
   - [Vector](#vector)

 * Numbers
   - [Rational](#rational)

### General mathematics

#### Math

```Math``` class contains common math functions that may be needed in various areas. The functions are implemented as static methods.

```php
// Check if the number equals zero within the given tolerance
$isZero = Math::isZero(0.000002);                       // Returns false
$isZero = Math::isZero(0.000002, 1e-3);                 // Returns true

// Check if the number does not equal zero within the given tolerance
$isNotZero = Math::isNotZero(0.000002);                 // Returns true
$isNotZero = Math::isNotZero(0.000002, 1e-3);           // Returns false

// Check if two numbers are equal within the given tolerance
$areEqual = Math::areEqual(0.000002, 0.000003);         // Returns false
$areEqual = Math::areEqual(0.000002, 0.000003, 1e-3);   // Returns true

// Compare two numeric arrays elementwise within the given tolerance
$areEqual = Math::areArraysEqual([1, 2, 3], [1, 2, 3]); // Returns true

// Find the greatest common divisor of two numbers
$gcd = Math::gcd(28, 35);       // Returns 7
```

### Linear Algebra

#### Matrix

Many matrix methods in PHPMathObjects are implemented in two versions: non-mutating (return a new matrix object) and mutating (change the existing matrix).
The latter method names have a letter "m" in the beginning, e.g. add() and mAdd(), transpose() and mTranspose(), etc.
You can decide which method is more suitable for a particular task. Usually the mutating methods are slightly faster than non-mutating ones because no new object instantiation is needed.

```php
// Create a new matrix object using class constructor
$matrix = new Matrix([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
]);

// Or use a suitable factory method
$matrix = Matrix::fill(3, 4, 0.1);          // Make a 3x4 matrix and fill its elements with 0.1
$matrix = Matrix::identity(3);              // Make a 3x3 identity matrix
$matrix = Matrix::random(5, 8, -3.5, 9.5)   // Make a 5x8 matrix filled with random float numbers between -3.5 and 9.5
$matrix = Matrix::randomInt(3, 3, 1, 10)    // Make a 3x3 matrix filled with random integer numbers between 1 and 10

// Matrix dimensions
$rows = $matrix->rows();
$columns = $matrix->columns();
$numberOfElements = $matrix->size(); 
$numberOfElements = count($matrix);         // Alternative way as Matrix implements "Countable" interface

// Get matrix as array
$array = $matrix->toArray(); 

// Element getters and setters (zero-based indexing)
$element = $matrix->get(1, 2);
$matrix->set(1, 2, -15.6);
$element = $matrix->set(1, 2, 100)->get(1, 2); // Set() method returns $this so it can be chained
$doesElementExist = $matrix->isSet(2, 1);

// Alternative getters and setters via "ArrayAccess" interface
$element = $matrix[[1, 2]];    // Note the format of the index. The problem is that PHP supports native ArrayAccess for 1D arrays only
$matrix[[1, 2]] = 15;
$doesElementExist = isset($matrix[[1, 2]]);

// Matrix properties
$isSquare = $matrix->isSquare(); 

// Compare matrices
$equal = $matrix->isEqual($anotherMatrix);          // Compare elementwise within a default tolerance of 1.0e^-8
$equal = $matrix->isEqual($anotherMatrix, 1e-8);    // Or set the tolerance explicitly
$equal = $matrix->isEqualExactly($anotherMatrix);   // Compare matrices elementwise with '===' operator

// Check if the matrix has zero elements only
$isZero = $matrix->isZero($tolerance);              // Check if zero elementwise within the given tolerance (default is 1.0e^-8)
$isZeroExactly = $matrix->isZeroExactly();          // Check if zero elementwise exactly using the '===' operator

// Matrix arithmetics
$sum = $matrix->add($anotherMatrix);
$difference = $matrix->subtract($anotherMatrix);
$multiplication = $matrix->multiply($anotherMatrix);
$multiplicationByScalar = $matrix->multiplyByScalar($scalarIntOrFloat);
$signsChanged = $matrix->changeSign();

// Matrix arithmetics (mutating methods)
$matrix->mAdd($anotherMatrix);
$matrix->mSubtract($anotherMatrix);
$matrix->mMultiply($anotherMatrix);
$matrix->mMultiplyByScalar($scalarIntOrFloat);
$matrix->mChangeSign();

// Matrix unary operations
$transpose = $matrix->transpose();
$trace = $matrix->trace();
$determinant = $matrix->determinant();
$rowEchelonForm = $matrix->ref();
$reducedRowEchelonForm = $matrix->rref();

// Matrix unary operations (mutating methods)
$matrix->mTranspose();
$matrix->mRef();             //Row echelon form
$matrix->mRref();            //Reduced row echelon form

// Matrix resizing and concatenation
$joinRight = $matrix->joinRight($anotherMatrix);
$joinBottom = $matrix->joinBottom($anotherMatrix);

// Matrix resizing and concatenation (mutating methods)
$matrix->mJoinRight($anotherMatrix);
$matrix->mJoinBottom($anotherMatrix);

 // Create a 1x2 submatrix starting from the element at row 0, column 0 till the element at row 1, column 2 
$submatrix = $matrix->submatrix(0, 0, 1, 2);

// Convert a matrix row or column to a Vector object
$rowVector = $matrix->rowToVector(1);
$columnVector = $matrix->columnToVector(2);

// Conversion to a string representation
$string = $matrix->toString();
$string = (string) $matrix;
// Both ways will return
// [1, 2, 3]
// [4, 5, 6]
// [7, 8, 9]
```

#### Vector

Vector class extends the Matrix class so all Matrix methods are available too. Some of them have additional wrappers for more convenient usage, e.g. fill() vs. vectorFill().

```php
// Create a new vector using the class constructor
$rowVector = new Vector([[1, 2, 3]]);
$columnVector = new Vector([
    [1],
    [2],
    [3],
]);

// Or use a suitable factory method
$vector = Vector::fromArray([1, 2, 3], VectorEnum::Column);   // Creates a [[1], [2], [3]] column vector
$vector = Vector::vectorFill(5, 1.1, VectorEnum::Row);         // Creates a [[1.1, 1.1, 1.1, 1.1, 1.1]] row vector
$vector = Vector::vectorRandom(5, -3.5, 9.5)   // Make a with 5-component vector filled with random float numbers between -3.5 and 9.5
$vector = Vector::randomInt(3, 1, 10)    // Make a 3-component vector filled with random integer numbers between 1 and 10

// Get the vector type (orientation)
$rowVector->vectorType();                 // Returns VectorEnum::Row
$columnVector->vectorType();              // Returns VectorEnum::Column

// Vector size
$numberOfComponents = $rowVector->size(); // Returns 3
$numberOfElements = count($rowVector);    // Alternative way as Vector implements "Countable" interface
$rows = $rowVector->rows();               // Returns 1
$columns = $rowVector->columns();         // Returns 3
$rows = $columnVector->rows();               // Returns 3
$columns = $columnVector->columns();         // Returns 1  

// Element getters and setters (zero-based indexing)
$element = $rowVector->vGet(2);            // Equivalent to get(1, 2) using the Matrix method
$rowVector->vSet(2, -15.6);                // Equivalent to set(1, 2, -15.6) using the Matrix method
$doesElementExist = $rowVector->vIsSet(2); // Equivalent to isSet(2) using the Matrix method 

// Alternative getters and setters via "ArrayAccess" interface
$element = $rowVector[2];    // Equivalent to $rowVector->vGet(2)
$rowVector[2] = 15;          // Equivalent to $rowVector->vSet(2, 15) 
$doesElementExist = isset($rowVector[2]);

// Get vector as an array
$columnVector->toArray();
/* Returns the 2D column array:
 * [
 *  [1],
 *  [2],
 *  [3],
 * ]
 */
$columnVector->toPlainArray();      // Returns a 1D array [1, 2, 3]

// Get a subvector
$subvector = $rowVector->subvector(1, 2); // Returns a new vector [2, 3]

// Vector binary operations
dotProduct = $rowVector->dotProduct($anotherVector);
$crossProduct = $rowVector->crossProduct($anotherVector);

// Conversion to a string representation
$string = $rowVector->toString();
$string = (string) $rowVector;
// Both ways will return
// [1, 2, 3]
$string = $columnVector->toString();
$string = (string) $columnVector;
// Both ways will return
// [1]
// [2]
// [3]
```

### Numbers

#### Rational

A class to store rational numbers and perform mathematical operations on them

```php
// Create a new rational number using class constructor Rational($whole, $numerator, $denominator)
$r = new Rational(5, 1, 2);             // Creates a rational number 1 1/2

// Or use a suitable class constructor
$r = Rational::fromString("-5 1/3");    // Equivalent to "new Rational(-5, -1, 3)"
$r = Rational::fromInt(15);             // Equivalent to "new Rational(15, 0, 1)"
$r = Rational::fromFloat(0.333333);     // Equivalent to "new Rational(0, 1, 3)"

// Convert a rational to a float
$r = Rational::fromString("1/3");
$float = $r->toFloat();                 // Returns 0.33333333333

// Convert a rational to a text form using toString() method
$string = $r->toString();               // Returns "-5 1/3"
// or string cast
$string = (string) $r;  

// Compare a rational number with zero
$isZero = $r->isZero();
// Or with another rational
$isEqual = $r->isEqual($anotherRational);

// Check if the rational number is negative
$isNegative = $r->isNegative();
// Or if it is positive
$isPositive = $r->isPositive();

// Check if the rational number is an integer
$isInteger = $r->isInteger();

// Calculate the reciprocal (multiplicative inverse)
$reciprocal = $r->reciprocal();

// Arithmetics with rationals
$r1 = Rational::fromString("2 3/8");
$r2 = Rational::fromString("4 9/17");
$sum = $r1->add($r2);
$difference = $r1->subtract($r2);
$multiplication = $r1->multiply($r2);
$division = $r1->divide($r2);
```
