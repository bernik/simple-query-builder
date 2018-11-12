<?php namespace sqb_tests\types; 

use function \sqb\{q, select, from};

describe("Example", function () {
    it("foo", function () {
        $q = q(
            select("name", "last_name"),
            from("users")
        );
        expect($q->toSql())
        ->toBe("SELECT name, last_name\nFROM users");
    });
});