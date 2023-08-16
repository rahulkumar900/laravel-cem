<?php

namespace App\Models;

use AWS\CRT\HTTP\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainComments extends Model
{
    use HasFactory;

    protected $table = "ticket_comments";

    protected $fillable = ["ticket_id", "comments", "commented_by"];


    public function complains()
    {
        return $this->belongsTo(Complain::class, 'ticket_id', 'id');
    }

    public function complainUsers(){
        return $this->hasOne(User::class, "commented_by", "id");
    }

    // save complin comment
    protected static function saveUserComment($ticket_id, $comments, $user_id)
    {
        return ComplainComments::create([
            "ticket_id"         =>      $ticket_id,
            "comments"          =>      $comments,
            "commented_by"      =>      $user_id
        ]);
    }

}
