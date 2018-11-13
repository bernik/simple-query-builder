<?php namespace sqb\predicates; 

use function \sqb\utils\{to_sql, format_predicates, paren_wrap};

class Not implements \sqb\protocols\Sqlable {
    function __construct ($form, ...$forms) { $this->form = $form; $this->forms = $forms; }
    function toSql () {
        return (empty($this->forms) and is_a($this->form, \sqb\protocols\Invertible::class))?
            $this->form->invert()->toSql():
            "NOT (".join(" AND ", to_sql(array_merge([$this->form], $this->forms))).")";
    }
}

class Eq extends \sqb\protocols\Invertible implements \sqb\protocols\Sqlable {
    function __construct ($field, $val, $nullsafe = false) { $this->field = $field; $this->val = $val; $this->nullsafe = $nullsafe; }
    function toSql () {
        switch (true) {
            case is_null($this->val) and !$this->inverted: return $this->field." IS NULL"; 
            case is_null($this->val) and $this->inverted: return $this->field." IS NOT NULL"; 
            case is_array($this->val) and !$this->inverted: return $this->field." IN (".join(", ", to_sql($this->val)).")"; 
            case is_array($this->val) and $this->inverted: return $this->field." NOT IN (".join(", ", to_sql($this->val)).")"; 
            case !$this->inverted and $this->nullsafe: return $this->field." <=> ".to_sql($this->val);
            case !$this->inverted and !$this->nullsafe: return $this->field." = ".to_sql($this->val);
            case $this->inverted: return $this->field." != ".to_sql($this->val);
        }
    }
}

class Lt implements \sqb\protocols\Sqlable {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." < ".to_sql($this->val); }
}

class Lte implements \sqb\protocols\Sqlable {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." <= ".to_sql($this->val); }
}

class Gt implements \sqb\protocols\Sqlable {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." > ".to_sql($this->val); }
}

class Gte implements \sqb\protocols\Sqlable {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." >= ".to_sql($this->val); }
}

class Using implements \sqb\protocols\Sqlable {
    function __construct (...$fields) { $this->fields = $fields; }
    function toSql () { return "USING (".join(", ", to_sql($this->fields)).")"; }
}

class Between implements \sqb\protocols\Sqlable {
    function __construct ($field, $min, $max) { $this->field = $field; $this->min = $min; $this->max = $max; }
    function toSql () { return $this->field." BETWEEN ".$this->min." AND ".$this->max; }
}

class Aand implements \sqb\protocols\Sqlable {
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return join(" AND ", paren_wrap(to_sql($this->preds))); }
}

class Oor implements \sqb\protocols\Sqlable {
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return join(" OR ", paren_wrap(to_sql($this->preds))); }
}

class Coalesce implements \sqb\protocols\Sqlable {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "COALESCE(".to_sql($this->forms).")"; }
}

class Greatest implements \sqb\protocols\Sqlable {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "GREATEST(".to_sql($this->forms).")"; }
}

class Least implements \sqb\protocols\Sqlable {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "GREATEST(".to_sql($this->forms).")"; }
}


