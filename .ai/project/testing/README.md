# Doctrine ORM Test-First Policy

Status: enabled
Owner: `@atvardovsky`
Decision authority: `@atvardovsky`
Last reviewed: 2026-08-21
Evidence revision: `454db525c`

Alatyr may recommend regression-first, characterization-first, or
contract-first tests before implementation when the task changes accepted ORM
behavior, persistence, query, mapping, validation, or security-sensitive logic.
This is advisory unless a task-specific approval makes it required.

Existing target commands remain authoritative. Local full PHPUnit runs with a 1G PHP memory limit and currently reports SQLite `SQRT()` errors in `Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` and `Doctrine\Tests\ORM\Functional\Ticket\GH7941Test::typesShouldBeConvertedForDQLFunctions`; use focused tests and record the blocker when applicable.
