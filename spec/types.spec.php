<?php namespace mqb_tests\types; 

require __DIR__."/../vendor/autoload.php"; 

use function \mqb\{q, select, from};

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