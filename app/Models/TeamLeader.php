<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeader extends Model
{
    use HasFactory;

    protected $table = 'team_leaders';
    protected $fillable = ['temple_id','access_temple_id'];

    public function temples()
    {
        return $this->hasOne(User::class, 'temple_id', 'access_temple_id');
    }

    protected static function getAccessableTempleIdes($temple_id)
    {
        return TeamLeader::with('temples')->where('temple_id', $temple_id)->get();
    }

}
