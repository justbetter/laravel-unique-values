<?php

namespace JustBetter\UniqueValues\Exceptions;

use Exception;

class MaxAttemptsException extends Exception
{
    public function __construct(string $scope, int $attempts, ?string $subject = null)
    {
        parent::__construct(vsprintf(
            'Failed to generate unique value after %s attempts for scope "%s"%s',
            [
                $attempts,
                $scope,
                $subject === null ? '' : ' and subject "'.$subject.'"',
            ]
        ));
    }
}
