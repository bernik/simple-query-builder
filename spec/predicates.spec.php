<?php namespace sqb_tests\predicates; 

use function \sqb\{eq, not};
use function \sqb\utils\{to_sql};

describe("equality", function () {

    it("=", function () {
        expect(to_sql(eq("foo", 42)))->toEqual("foo = 42");
        expect(to_sql(eq("foo", ":foo")))->toEqual("foo = :foo");
    });

    it("checks null values", function () {
        expect(to_sql(eq("foo", null)))->toEqual("foo IS NULL");
        expect(to_sql(eq("foo", null, true)))->toEqual("foo IS NULL");
        expect(to_sql(eq("foo", "bar", true)))->toEqual("foo <=> bar");
    });

    it("convert arrays to `in` ", function () {
        expect(to_sql(eq("foo", [])))->toEqual("foo IN ()");
        expect(to_sql(eq("foo", [1,2,3])))->toEqual("foo IN (1, 2, 3)");
        expect(to_sql(eq("foo", [], true)))->toEqual("foo IN ()");
    });

});