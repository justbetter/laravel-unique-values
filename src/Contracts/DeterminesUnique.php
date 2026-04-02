<?php

declare(strict_types=1);

namespace JustBetter\UniqueValues\Contracts;

interface DeterminesUnique
{
    public function unique(string $scope, string $value, ?string $subject = null): bool;
}
