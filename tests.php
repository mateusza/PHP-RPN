#!/usr/bin/env php
<?php

include "rpn.php";

$tests = [
    [ "2 5 +",              7 ],
    [ "1000 4 /",           250 ],
    [ "400 4 + 200 2 / +",  504 ],
    [ "123 123 + + + + ",            123 ],
    [ "2000 -100 2 2 + 3 1 + == if", 2000 ],
    [ "3 4 2 * 1 5 - 2 3 ^ ^ / + ", 3.0001220703125 ],
    [ "3 4 2 * 1 5 - 2 3 ^ ^ / + 3.0001220703125 ==", TRUE ],
    [ "4 5 + 3 * 1 +", 28 ],
    [ "2 7 + 3 / 14 3 - 4 * + 2 /", 23.5 ],
    [ "4 2 > 3 3 + ==", 1 ]
];

foreach( $tests as $test ){
    echo "Testing [$test[0]]: ";
    try {
        $v = rpn_eval( $test[0] );
    }
    catch( Exception $e ){
        echo "ERROR: [{$e->getMessage()}]\n";
        $v = null;
    }
    echo "= $v " . ( $v == $test[1] ? "OK" : "INCORRECT, EXPECTING ($test[1])" ) . "\n";
}

echo "cos(pi) = " . rpn_eval( "pi cos", [
    "cos" => function( &$stack ){ $a = array_pop( $stack ); $stack[] = cos( $a ); },
    "pi" => 3.1415926535
] ) . "\n";

echo "sin(PI/2) = " . rpn_eval( "pi 2 / sin", function( $op ){
    if ( $op == "pi" ) return 3.1415926535;
    if ( $op == "sin" ) return function( &$stack ){ $a = array_pop( $stack ); $stack[] = sin( $a ); };
    return null;
} ) . "\n";

