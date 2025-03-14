<?php

namespace JustBetter\UniqueValues\Support;

use Closure;
use JustBetter\UniqueValues\Contracts\DeterminesUnique;
use JustBetter\UniqueValues\Exceptions\MaxAttemptsException;
use JustBetter\UniqueValues\Models\UniqueValue as Model;

class UniqueValue
{
    public int $attempts = 3;

    public string $scope;

    public ?string $subject = null;

    public bool $override = false;

    public Closure $generator;

    public function __construct(protected DeterminesUnique $determinesUnique) {}

    public static function make(): UniqueValue
    {
        /** @var UniqueValue $uniqueValues */
        $uniqueValues = app(UniqueValue::class);

        return $uniqueValues;
    }

    public function attempts(int $attempts): static
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function scope(string $scope): static
    {
        $this->scope = $scope;

        return $this;
    }

    public function subject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function override(bool $override = true): static
    {
        $this->override = $override;

        return $this;
    }

    public function generator(Closure $generator): static
    {
        $this->generator = $generator;

        return $this;
    }

    public function generate(): string
    {
        if ($this->subject !== null) {
            $currentValue = Model::query()
                ->where('scope', '=', $this->scope)
                ->where('subject', '=', $this->subject)
                ->first();

            if ($currentValue !== null) {
                if (! $this->override) {
                    return $currentValue->value;
                }

                $currentValue->delete();
            }
        }

        $attempts = $this->attempts;

        for ($attempt = 0; $attempt < $attempts; $attempt++) {

            $generatedValue = ($this->generator)($attempt);

            if ($this->determinesUnique->unique($this->scope, $generatedValue, $this->subject)) {
                return $generatedValue;
            }
        }

        throw new MaxAttemptsException(
            $this->scope,
            $attempts,
            $this->subject,
        );
    }
}
