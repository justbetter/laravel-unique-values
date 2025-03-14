<?php

namespace JustBetter\UniqueValues\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $scope
 * @property string $value
 * @property ?string $subject
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class UniqueValue extends Model
{
    protected $guarded = [];
}
