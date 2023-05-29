<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationStatus extends Model
{
    use HasFactory;

    const PENDING = 1;
    const ON_SALE = 2;
    const PAUSED = 3;
    const CANCELED = 4;
    
    protected $table = "publications_status";

    protected $fillable = ["name"];

}
