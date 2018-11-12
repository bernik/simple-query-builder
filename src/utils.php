<?php namespace sqb\utils; 


function to_sql($x) {
    switch (true) {
        case \is_object($x): return $x->toSql();
        case \is_array($x): return array_map('\sqb\utils\to_sql', $x);
        default: return $x;
    }
}

function format_predicates ($preds) { 
    return join(" AND ", paren_wrap(to_sql($preds)));
}

function paren_wrap ($x) {
    return is_array($x)?
        array_map(function ($_x) { return "(".$_x.")"; } , $x):
        "(".$x.")";
} 