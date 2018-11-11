<?php namespace mqb\predicates; 

use function \mqb\utils\{to_sql, format_predicates, paren_wrap};

class Not {
    function __construct ($form) { $this->form = $form; }
    function toSql () {
        return $form instanceof Eq?
            $form->complement()->toSql():
            "NOT (".to_sql($form).")";
    }
}

class Eq {
    public $complement = false;
    function __construct ($field, $val, $nullsafe = false) { $this->field = $field; $this->val = $val; $this->nullsafe = $nullsafe; }
    function complement () { $this->complement = !$this->complement; return $this; }
    function toSql () {
        switch (true) {
            case is_null($this->val) and !$this->complement: return $this->field." IS NULL"; 
            case is_null($this->val) and $this->complement: return $this->field." IS NOT NULL"; 
            case is_array($this->val) and !$this->complement: return $this->field." IN (".join(", ", to_sql($this->val)).")"; 
            case is_array($this->val) and $this->complement: return $this->field." NOT IN (".join(", ", to_sql($this->val)).")"; 
            case !$this->complement and $this->nullsafe: return $this->field." <=> ".to_sql($this->val);
            case !$this->complement and !$this->nullsafe: return $this->field." = ".to_sql($this->val);
            case $this->complement: return $this->field." != ".to_sql($this->val);
        }
    }
}

class Lt {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." < ".to_sql($this->val); }
}

class Lte {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." <= ".to_sql($this->val); }
}

class Gt {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." > ".to_sql($this->val); }
}

class Gte {
    function __construct ($field, $val) { $this->field = $field; $this->val = $val; }
    function toSql () { return $this->field." >= ".to_sql($this->val); }
}

class Using {
    function __construct (...$fields) { $this->fields = $fields; }
    function toSql () { return "USING (".join(", ", to_sql($this->fields)).")"; }
}

class Between {
    function __construct ($field, $min, $max) { $this->field = $field; $this->min = $min; $this->max = $max; }
    function toSql () { return $this->field." BETWEEN ".$this->min." AND ".$this->max; }
}

class Aand {
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return join(" AND ", paren_wrap(to_sql($this->preds))); }
}

class Oor {
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return join(" OR ", paren_wrap(to_sql($this->preds))); }
}

class Coalesce {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "COALESCE(".to_sql($this->forms).")"; }
}

class Greatest {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "GREATEST(".to_sql($this->forms).")"; }
}

class Least {
    function __construct (...$forms) { $this->forms = $forms; }
    function toSql () { return "GREATEST(".to_sql($this->forms).")"; }
}


