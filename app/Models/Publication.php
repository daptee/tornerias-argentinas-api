<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Publication extends Model
{
    use HasFactory;

    const SELECT_INDEX = [
        'id',
        'title',
        'price',
        'status_id'
    ];

    const INDEX = [
        'status',
        'categories.category',
        'files'
    ];

    const SELECT_SHOW = [
        'id',
        'title',
        'price',
        'description',
        'stock',
        'status_id'
    ];

    const SHOW = [
        'status',
        'categories.category',
        'files'
    ];

    protected $fillable = [
        'title',
        'stock',
        'price',
        'description',
        'status_id',
    ];

    protected $hidden = [
        'status_id'
    ];

    public function status(): HasOne
    {
        return $this->hasOne(PublicationStatus::class, 'id', 'status_id');
    }

    public function categories()
    {
        return $this->hasMany(PublicationCategory::class, 'publication_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(PublicationFile::class, 'publication_id', 'id');
    }
}
