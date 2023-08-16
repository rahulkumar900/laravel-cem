<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dailyActivity extends Model
{
    use HasFactory;

    protected $table = 'dailyActivities';
    protected $fillable = [
        'is_lead',
        'user_id',
        'is_paid',
        'gender',
        'analytic_date',
        'day1',
        'day2',
        'day3',
        'day4',
        'day5',
        'day6',
        'day7',
        'day8',
        'day9',
        'day10',
        'day11',
        'day12',
        'day13',
        'day14',
        'day15',
        'day16',
        'day17',
        'day18',
        'day19',
        'day20',
        'day21',
        'day22',
        'day23',
        'day24',
        'day25',
        'day26',
        'day27',
        'day28',
        'day29',
        'day30',
        'created_at',
        'updated_at',
        'is_active'
    ];
}
