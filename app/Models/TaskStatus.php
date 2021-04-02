<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    const STATUS_CREATED = 1;
    const STATUS_ON_PROGRESS = 2;
    const STATUS_FINISH = 3;
    const STATUS_FAILED = 4;

    protected $fillable = [
        'task_id',
        'status'
    ];
    protected $appends = [
        'status_label'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function getStatusLabelAttribute()
    {
        $lists = self::statusLists();
        if (isset($this->status) && in_array($this->status,array_keys($lists))) {
            return $lists[$this->status];
        }
        return 'Unknown';
    }

    public static function statusLists()
    {
        return [
            self::STATUS_CREATED => 'Created',
            self::STATUS_ON_PROGRESS => 'On progress',
            self::STATUS_FINISH => 'Finish',
            self::STATUS_FAILED => 'Failed',
        ];
    }
}
