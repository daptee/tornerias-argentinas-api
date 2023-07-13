<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class formContact extends Model
{
    use HasFactory;

    protected $table = "contact";

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'text',
    ];

}
