<?php namespace sqb_tests\types; 

use function \sqb\{
    q
    , select
    , from
    , join
    , left_join
    , using
    , aand
    , oor
    , eq
    , gte
    , where
};

describe("Example", function () {
    it("foo", function () {
        $q = q(
            select("name", "last_name"),
            from("users")
        );
        expect($q->toSql())
        ->toBe("SELECT name, last_name\nFROM users");
    });

    it('can joins', function () {
        $q = q(
            select("u.name", "u.last_name", "a.age"),
            from("users", "u"),
            join(["user_ages", "a"], using("user_id")),
            where(oor(
                aand(eq("u.name", "'John'"), eq("u.last_name", "'Doe'")),
                gte("a.age", 18)
            ))
        );
        expect($q->toSql())
        ->toBe(
"SELECT u.name, u.last_name, a.age
FROM users AS u
INNER JOIN user_ages AS a USING (user_id)
WHERE (((u.name = 'John') AND (u.last_name = 'Doe')) OR (a.age >= 18))");
    });
});