<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationStatusHistory extends Model
{
    use HasFactory;

    protected $table = "publications_status_history";

    protected $fillable = [
        'publication_id',
        'status_id'
    ];

}
