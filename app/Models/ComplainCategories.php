<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainCategories extends Model
{
    use HasFactory;

    protected $table = "complainCategories";

    protected function getComplainCategory()
    {
        return ComplainCategories::all();
    }
}
