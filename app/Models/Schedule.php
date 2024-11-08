<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['ucode', 'start_time', 'end_time', 'description', 'class'];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'ucode';
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->start_time)->locale('id')->isoFormat('DD MMMM YYYY');
    }

    public function getFormattedTimeAttribute(): string
    {
        $startTime = Carbon::parse($this->start_time)->format('H:i');
        $endTime = Carbon::parse($this->end_time)->format('H:i');
        return "{$startTime} - {$endTime}";
    }
}
