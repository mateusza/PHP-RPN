<?php

function rpn_eval( $exp, $custom_ops = [] ){
    $OPERS = [

        // basic maths

        "+" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $b + $a;
        },
        "-" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $b - $a;
        },
        "*" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $b * $a;
        },
        "/" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $b / $a;
        },

        // sign operation

        "neg" => function( &$stack ){
            $a = array_pop( $stack );
            $stack[] = -$a;
        },

        "abs" => function( &$stack ){
            $a = array_pop( $stack );
            $stack[] = abs( $a );
        },

        "sgn" => function( &$stack ){
            $a = array_pop( $stack );
            $stack[] = $a < 0 ? -1 : ( $a > 0 ? 1 : 0 );
        },

        // power

        "^" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = pow( $b, $a );
        },

        "pow" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = pow( $b, $a );
        },

        // integral division

        "mod" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $b % $a;
        },

        "div" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = intdiv( $b, $a );
        },

        // stack operations

        "dup" => function( &$stack ){
            $a = array_pop( $stack );
            $stack[] = $a;
            $stack[] = $a;
        },

        "swp" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $a;
            $stack[] = $b;
        },

        // comparisons

        "==" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b == $a );
        },

        "!=" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b != $a );
        },

        ">" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b > $a );
        },

        "<" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b < $a );
        },

        ">=" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b >= $a );
        },

        "<=" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $b <= $a );
        },

        "between" => function( &$stack ){
            $x = array_pop( $stack );
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = ( $x < $a && $x > $b );
        },

        // conditional

        "if" => function( &$stack ){
            $cond = array_pop( $stack );
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $cond ? $b : $a;
        },

        // logic
        
        "and" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $a && $b;
        },

        "or" => function( &$stack ){
            $a = array_pop( $stack );
            $b = array_pop( $stack );
            $stack[] = $a || $b;
        },

        "not" => function( &$stack ){
            $a = array_pop( $stack );
            $stack[] = !( $a );
        },

    ];

    $tokens = is_string( $exp ) ? explode( " ", $exp ) : $exp;
    if ( ! is_array( $tokens ) ){
        throw new Exception("RPN: Unsupported expression type");
    }

    $stack = [];
    foreach( $tokens as $token ){
        if ( "" === $token ){
            continue;
        }
        if ( is_numeric( $token ) ){
            $stack[] = $token;
        }
        else {

            if ( ! is_null( $custom_ops ) ){
                $op = null;
                if ( is_callable( $custom_ops ) ){
                    $op = $custom_ops( $token );
                }
                if ( is_array( $custom_ops ) && array_key_exists( $token, $custom_ops ) ){
                    $op = $custom_ops[ $token ];
                }

                if ( is_callable( $op ) ){
                    $op( $stack );
                    continue;
                }
                if ( ! is_null( $op ) ){
                    $stack[] = $op;
                    continue;
                }
            }

            if ( array_key_exists( $token, $OPERS ) ){
                $op = $OPERS[ $token ];
            }
            if ( is_callable( $op ) ){
                $op( $stack );
                continue;
            }
            if ( ! is_null( $op ) ){
                $stack[] = $op;
                continue;
            }

            throw new Exception("RPN: Unknown operation [$token]");
        }
    }
    if ( count( $stack ) > 1 ){
        throw new Exception("RPN: Multiple values left on stack: " . implode( " ", $stack ) );
    }

    return $stack[0];
}


