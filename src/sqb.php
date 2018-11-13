<?php namespace sqb;

use \sqb\types as t;
use \sqb\predicates as pred;
use \sqb\utils as utils; 

// types
function query (...$forms) { return new t\Query(...$forms); } 
function q (...$forms) { return new t\Query(...$forms); } 
function with (...$forms) { return new t\With(...$forms); }           
function with_recursive (...$forms) { return new t\WithRecursive(...$forms); }           
function intersect (...$forms) { return new t\Intersect(...$forms); }           
function union (...$forms) { return new t\Union(...$forms); }           
function unionall (...$forms) { return new t\UnionAll(...$forms); }           
function insert (...$forms) { return new t\Insert(...$forms); }           
function select (...$forms) { return new t\Select(...$forms); }           
function update (...$forms) { return new t\Update(...$forms); }           
function delete (...$forms) { return new t\Delete(...$forms); }           
function delete_from (...$forms) { return new t\DeleteFrom(...$forms); }           
function columns (...$forms) { return new t\Columns(...$forms); }           
function from (...$forms) { return new t\From(...$forms); }           
function join (...$forms) { return new t\Join(...$forms); }           
function left_join (...$forms) { return new t\LeftJoin(...$forms); }           
function right_join (...$forms) { return new t\RightJoin(...$forms); }           
function full_join (...$forms) { return new t\FullJoin(...$forms); }           
function set (...$forms) { return new t\Set(...$forms); }           
function where (...$forms) { return new t\Where(...$forms); }           
function group_by (...$forms) { return new t\GroupBy(...$forms); }           
function having (...$forms) { return new t\Having(...$forms); }           
function order_by (...$forms) { return new t\OrderBy(...$forms); }           
function limit (...$forms) { return new t\Limit(...$forms); }           
function offset (...$forms) { return new t\Offset(...$forms); }           
function lock (...$forms) { return new t\Lock(...$forms); }           
function values (...$forms) { return new t\Values(...$forms); }           
function raw ($str) { return new t\Raw($str); }           


// predicates 
function not (...$forms) { return new pred\Not(...$forms); }
function eq ($field, $val, $nullsafe = false) { return new pred\Eq($field, $val, $nullsafe); }
function lt ($field, $val) { return new pred\Lt($field, $val); }
function lte ($field, $val) { return new pred\Lte($field, $val); }
function gt ($field, $val) { return new pred\Gt($field, $val); }
function gte ($field, $val) { return new pred\Gte($field, $val); }
function using (...$fields) { return new pred\Using(...$fields); }
function between ($field, $min, $max) { return new pred\Between($field, $min, $max); }
function aand (...$preds) { return new pred\Aand(...$preds); }
function oor (...$preds) { return new pred\Oor(...$preds); }
function coalesce (...$forms) { return new pred\Coalesce(...$forms); }
function greatest (...$forms) { return new pred\Greatest(...$forms); }
function least (...$forms) { return new pred\Least(...$forms); }