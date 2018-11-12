<?php namespace sqb\types; 

use function \sqb\utils\{to_sql, format_predicates, paren_wrap};

class QueryModifiers {
    function __construct ($modifiers) {
        
    }
}

class Query {

    function __construct (...$statements) {
        $this->statements = $statements;
    }

    function toSql () {
        $priorities = array_flip([
            'sqb\types\With',           
            'sqb\types\WithRecursive',           
            'sqb\types\Intersect',           
            'sqb\types\Union',           
            'sqb\types\UnionAll',           
            'sqb\types\Insert',           
            'sqb\types\Select',           
            'sqb\types\Update',           
            'sqb\types\Delete',           
            'sqb\types\DeleteFrom',           
            'sqb\types\Columns',           
            'sqb\types\From',           
            'sqb\types\Join',           
            'sqb\types\LeftJoin',           
            'sqb\types\RightJoin',           
            'sqb\types\FullJoin',           
            'sqb\types\Set',           
            'sqb\types\Where',           
            'sqb\types\GroupBy',           
            'sqb\types\Having',           
            'sqb\types\OrderBy',           
            'sqb\types\Limit',           
            'sqb\types\Offset',           
            'sqb\types\Lock',           
            'sqb\types\Values',           
        ]);
        usort($this->statements, function ($a, $b) use ($priorities) {
            return $priorities[\get_class($a)] <=> $priorities[\get_class($b)];
        });

        return join("\n", to_sql($this->statements));
    }

}

class With { 
    function __construct (...$bindings) { $this->bindings = $bindings; }
    function toSql () {
        return 'WITH '
            .join(", \n", 
                array_map(
                    function ($binding) {
                        list(list($name, $cols), $expr) = $binding;
                        return $name.($cols? ' '.to_sql($cols) : '').
                            ' AS ('.to_sql($expr).')';
                    },
                    $this->bindings
                ));
    }
}

class WithRecursive { 
    function __construct (...$bindings) { $this->bindings = $bindings; }
    function toSql () {
        return 'WITH RECURSIVE '
            .join(", \n", 
                array_map(
                    function ($binding) {
                        list(list($name, $cols), $expr) = $binding;
                        return $name.($cols? ' '.to_sql($cols) : '').
                            ' AS ('.to_sql($expr).')';
                    },
                    $this->bindings
                ));
    }
}
// @todo
class Intersect { 
    function __construct () {}
    function toSql () {}
}

class Union { 
    function __construct (...$queries) { $this->queries = $queries; }
    function toSql () { return join("\nUNION\n", paren_wrap($this->queries)); }
}

class UnionAll { 
    function __construct (...$queries) { $this->queries = $queries; }
    function toSql () { return join("\nUNION ALL\n", paren_wrap($this->queries)); }
}

class Insert { 
    function __construct ($table, $alias = '') { $this->table = $table; $this->alias = $alias; }
    function toSql () { return "INSERT INTO ".$this->table.($this->alias? ' AS '.$this->alias:""); }
}

class Select { 
    function __construct (...$fields) { $this->fields = $fields; }
    function toSql () {
        return "SELECT ".join(", ", to_sql($this->fields));
    }
}

class Update { 
    function __construct ($table, $alias = '') { $this->table = $table; $this->alias = $alias;}
    function toSql () { 
        return "UPDATE ".$this->table.($this->alias? " AS ".$this->alias:"");
    }
}

class Delete { 
    function __construct (...$tables) { $this->tables = $tables; }
    function toSql () {
        return "DELETE ".join(", ", $this->tables );
    }
}

class DeleteFrom { 
    function __construct ($table) { $this->table = $table; }
    function toSql () { return "DELETE FROM ".$this->table; }
}

class Columns { 
    function __construct (...$columns) { $this->columns = $columns; }
    function toSql () { return "(".join(", ", $this->columns).")"; }
}

class From { 
    function __construct ($table, $alias = '') { $this->table = $table; $this->alias = $alias; }
    function toSql () { return "FROM ".$this->table.($this->alias? " AS ".$this->alias: "");}
}

class Join { 
    function __construct ($table, ...$preds) { $this->table = $table; $this->preds = $preds; }
    function toSql () {
        if (\is_array($this->table)) {
            list($table, $alias) = $this->table; 
            $_table = $table." AS ".$alias;
        } else {
            $_table = $this->table; 
        }
        $_preds = $this->preds[0] instanceof \sqb\predicates\Using?
            " USING ".to_sql($preds[0]):
            " ON ".join(" AND ", to_sql($this->preds));
        return "INNER JOIN ".$_table.$_preds;
    }
}

class LeftJoin { 
    function __construct ($table, ...$preds) { $this->table = $table; $this->preds = $preds; }
    function toSql () {
        if (\is_array($this->table)) {
            list($table, $alias) = $this->table; 
            $_table = $table." AS ".$alias;
        } else {
            $_table = $this->table; 
        }
        $_preds = $this->preds[0] instanceof \sqb\predicates\Using?
            " USING ".to_sql($preds[0]):
            " ON ".join(" AND ", to_sql($this->preds));
        return "LEFT JOIN ".$_table.$_preds;
    }
}

class RightJoin { 
    function __construct ($table, ...$preds) { $this->table = $table; $this->preds = $preds; }
    function toSql () {
        if (\is_array($this->table)) {
            list($table, $alias) = $this->table; 
            $_table = $table." AS ".$alias;
        } else {
            $_table = $this->table; 
        }
        $_preds = $this->preds[0] instanceof \sqb\predicates\Using?
            " USING ".to_sql($preds[0]):
            " ON ".join(" AND ", to_sql($this->preds));
        return "RIGHT JOIN ".$_table.$_preds;
    }
}

class FullJoin { 
    function __construct () {}
    function toSql () {}
}

class Set { 
    function __construct (...$kvs) { $this->kvs = $kvs; }
    function toSql () {
        $pairs = [];
        for ($i = 0; $i < count($this->kvs); $i += 2) {
            $pairs[] = [$this->kvs[$i], $this->kvs[$i+1]];
        }
        return "SET ".join(",\n", 
            array_map(
                function ($pair) {
                    list($k, $v) = $pair;
                    return to_sql($k)." = ".to_sql($v);
                }, 
                $pairs));
    }
}

class Where { 
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return "WHERE ".format_predicates($this->preds); }
}

class GroupBy { 
    function __construct (...$fields) { $this->fields = $fields; }
    function toSql () {
        return "GROUP BY ".join(", ", to_sql($this->fields));
    }
}

class Having { 
    function __construct (...$preds) { $this->preds = $preds; }
    function toSql () { return "HAVING ".format_predicates($this->preds); }
}

class OrderBy { 
    function __construct (...$orders) { $this->orders = $orders; }
    function toSql () {
        return join(", ", 
            array_map(
                function ($order) {
                    if (\is_array($order)) {
                        list($column, $direction) = $order;
                    } else {
                        $column = $order;
                        $direction = 'ASC';
                    }
                    return $column." ".\strtoupper($direction);
                },
                $this->orders
            )
        );
    }
}

class Limit { 
    function __construct ($limit) { $this->limit = $limit; }
    function toSql () { return "LIMIT ".$this->limit; }
}

class Offset { 
    function __construct ($offset) { $this->offset = $offset; }
    function toSql () { return "OFFSET ".$this->offset; }
}

class Lock { 
    function __construct ($lock) { $this->lock = $lock; }
    function toSql () { return \strtoupper($this->lock); }
}

class Values { 
    function __construct (...$vals) { $this->vals = $vals; }
    function toSql () {
        return paren_wrap(
            join(", ", 
                array_map(
                    function ($rowVals) { return "(".join(", ", $rowVals);}, 
                    $this->vals)));
    }
}


class Raw {
    function __construct (string $str) { $this->str = $str; }
    function toSql () { return $this->str; }
}