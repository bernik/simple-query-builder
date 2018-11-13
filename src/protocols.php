<?php namespace sqb\protocols; 

interface Sqlable {
    function toSql (); 
}

abstract class Invertible {
    public $inverted = false; 
    function invert() { $this->inverted = !$this->inverted; return $this; } 
}
