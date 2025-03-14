<?php

namespace JustBetter\UniqueValues\Tests\UniqueValues;

use JustBetter\UniqueValues\Exceptions\MaxAttemptsException;
use JustBetter\UniqueValues\Models\UniqueValue as Model;
use JustBetter\UniqueValues\Support\UniqueValue;
use JustBetter\UniqueValues\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UniqueValueTest extends TestCase
{
    #[Test]
    public function it_generates_unique_values(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->generator(function (int $attempt): string {
                return match ($attempt) {
                    0 => 'unique-value',
                    default => 'unique-value-'.$attempt,
                };
            });

        $this->assertEquals('unique-value', $generator->generate());
        $this->assertEquals('unique-value-1', $generator->generate());
    }

    #[Test]
    public function it_has_max_attempts(): void
    {
        $generator = UniqueValue::make()
            ->attempts(2)
            ->scope('::scope::')
            ->generator(fn (): string => 'unique-value');

        $this->assertEquals('unique-value', $generator->generate());

        $this->expectException(MaxAttemptsException::class);

        $generator->generate();
    }

    #[Test]
    public function it_does_not_generate_new_value_for_existing_subjects(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->subject('::subject::')
            ->generator(function (int $attempt): string {
                return match ($attempt) {
                    0 => 'unique-value',
                    default => 'unique-value-'.$attempt,
                };
            });

        $this->assertEquals('unique-value', $generator->generate());
        $this->assertEquals('unique-value', $generator->generate());
    }

    #[Test]
    public function it_can_override_values(): void
    {
        $generator = UniqueValue::make()
            ->scope('::scope::')
            ->subject('::subject::')
            ->override()
            ->generator(function (int $attempt): string {
                return match ($attempt) {
                    0 => 'unique-value',
                    default => 'unique-value-'.$attempt,
                };
            });

        $model = Model::query()->create([
            'scope' => '::scope::',
            'value' => '::value::',
            'subject' => '::subject::',
        ]);

        $this->assertEquals('unique-value', $generator->generate());
        $this->assertModelMissing($model);
    }
}
