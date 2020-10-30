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
