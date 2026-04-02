<?php

declare(strict_types=1);

namespace JustBetter\UniqueValues\Tests\UniqueValues;

use JustBetter\UniqueValues\Exceptions\MaxAttemptsException;
use JustBetter\UniqueValues\Models\UniqueValue as Model;
use JustBetter\UniqueValues\Support\UniqueValue;
use JustBetter\UniqueValues\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class UniqueValueTest extends TestCase
{
    #[Test]
    public function it_generates_unique_values(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->generator(fn (int $attempt): string => match ($attempt) {
                0 => 'unique-value',
                default => 'unique-value-'.$attempt,
            });

        $this->assertSame('unique-value', $generator->generate());
        $this->assertSame('unique-value-1', $generator->generate());
    }

    #[Test]
    public function it_has_max_attempts(): void
    {
        $generator = UniqueValue::make()
            ->attempts(2)
            ->scope('::scope::')
            ->generator(fn (): string => 'unique-value');

        $this->assertSame('unique-value', $generator->generate());

        $this->expectException(MaxAttemptsException::class);

        $generator->generate();
    }

    #[Test]
    public function it_does_not_generate_new_value_for_existing_subjects(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->subject('::subject::')
            ->generator(fn (int $attempt): string => match ($attempt) {
                0 => 'unique-value',
                default => 'unique-value-'.$attempt,
            });

        $this->assertSame('unique-value', $generator->generate());
        $this->assertSame('unique-value', $generator->generate());
    }

    #[Test]
    public function it_can_override_values(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->subject('::subject::')
            ->override()
            ->generator(fn (int $attempt): string => match ($attempt) {
                0 => 'unique-value',
                default => 'unique-value-'.$attempt,
            });

        $model = Model::query()->create([
            'scope' => '::scope::',
            'value' => '::value::',
            'subject' => '::subject::',
        ]);

        $this->assertSame('unique-value', $generator->generate());
        $this->assertModelMissing($model);
    }
}
