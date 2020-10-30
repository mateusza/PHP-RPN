<?php

require_once "rpn.php";

while( true ){
    $exp = readline("RPN> ");
    readline_add_history( $exp );
    try {
        $v = rpn_eval( $exp );
        print( json_encode( $v ) . "\n\n" );
    }
    catch ( Exception $e ){
        echo "ERROR: {$e->getMessage()}\n";
    }
}
