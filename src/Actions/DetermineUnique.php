<?php

namespace JustBetter\UniqueValues\Actions;

use JustBetter\UniqueValues\Contracts\DeterminesUnique;
use JustBetter\UniqueValues\Models\UniqueValue;

class DetermineUnique implements DeterminesUnique
{
    public function unique(string $scope, string $value, ?string $subject = null): bool
    {
        $lock = cache()->lock('unique-values:'.$scope);

        /** @var int $blockFor */
        $blockFor = config('unique-values.block_for', 10);

        try {
            $lock->block($blockFor);

            $unique = UniqueValue::query()
                ->where('scope', '=', $scope)
                ->where('value', '=', $value)
                ->doesntExist();

            if ($unique) {
                UniqueValue::query()->create([
                    'scope' => $scope,
                    'value' => $value,
                    'subject' => $subject,
                ]);
            }

            return $unique;
        } finally {
            $lock->release();
        }
    }

    public static function bind(): void
    {
        app()->singleton(DeterminesUnique::class, static::class);
    }
}
