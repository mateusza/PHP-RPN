# PHP RPN

Simple Reverse Polish Notation evaluator written in PHP.

## Usage:

```
    <?php
    require 'rpn.php'

    $x = rpn_eval( "2 3 +" ); // should be 5
    echo "x = $x\n";
```
## Supported operations

### Basic maths
- `+` - addition (`2 2 +` = `4`)
- `-` - subtraction (`10 2 -` = `8`)
- `*` - multiplication (`7 8 *` = `56`)
- `/` - division (floating point) (`10 4 /` = `2.5`)

### Sign
- `neg` - negative (`5 neg` = `-5`; `-13 neg` = `13`)
- `abs` - absolute value (`-7 abs` = `7`; `17 abs` = `17`)
- `sgn` - sign (`-123 sgn` = `-1`; `123 sgn` = `1`; `0 sgn` = `0`)

### Power
- `^` - power (`2 8 ^` = `256`)
- `pow` - power (`2 8 pow` = `256`)

### Integral division
- `mod` - remainder (`100 3 mod` = `1`)
- `div` - integral division (`100 3 mod` = `33`)

### Stack operations
- `dup` - duplicate last value on stack (`... 4 dup` becomes `... 4 4`)
- `swp` - swap last two values on stack (`... 10 20 swp` becomes `... 20 10`)

### Comparisons
All operations return `1` as `true` and `0` as `false`.

- `==` - equals (`3 3 ==` = `1`)
- `>`
- `<`
- `>=`
- `<=`
- `between` - value is between two values (`2 10 5 between` = `1`; `1 5 1 between` = `0`; `5 10 15 between` = `0`)
