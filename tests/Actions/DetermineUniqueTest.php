<?php

namespace JustBetter\UniqueValues\Tests\Actions;

use JustBetter\UniqueValues\Actions\DetermineUnique;
use JustBetter\UniqueValues\Models\UniqueValue;
use JustBetter\UniqueValues\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DetermineUniqueTest extends TestCase
{
    #[Test]
    public function it_determines_unique_value(): void
    {
        /** @var DetermineUnique $action */
        $action = app(DetermineUnique::class);
        $this->assertTrue($action->unique('suffix', 'unique-string'));

        /** @var ?UniqueValue $uniqueValue */
        $uniqueValue = UniqueValue::query()->firstWhere('value', '=', 'unique-string');

        $this->assertNotNull($uniqueValue);
        $this->assertFalse($action->unique('suffix', 'unique-string'));
    }
}
