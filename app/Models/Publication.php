<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory, SoftDeletes;

    const SELECT_INDEX = [
        'id',
        'title',
        'price',
        'status_id'
    ];

    const INDEX = [
        'user',
        'status',
        'categories.category',
        'files'
    ];

    const SELECT_SHOW = [
        'id',
        'user_id',
        'title',
        'price',
        'description',
        'stock',
        'status_id'
    ];

    const SHOW = [
        'user.locality',
        'status',
        'categories.category',
        'files',
        'publication_qualifications',
        'seller_qualifications',
        'questions_answer'
    ];

    protected $fillable = [
        'user_id',
        'title',
        'stock',
        'price',
        'description',
        'status_id',
    ];

    protected $hidden = [
        'user_id',
        'status_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

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

    public function publication_qualifications()
    {
        return $this->hasMany(PublicationQualification::class, 'publication_id', 'id');
    }

    public function seller_qualifications()
    {
        return $this->hasMany(SellerQualification::class, 'user_id', 'id');
    }

    public function questions_answer()
    {
        return $this->hasMany(PublicationQuestionAnswer::class, 'publication_id', 'id');
    }
}
