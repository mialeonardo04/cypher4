<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_overtime extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_overtime';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_overtime';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }
}
