<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory;

    const SELECT_INDEX = [
        'id',
        'title',
        'price',
        'user_id',
        'description',
        'stock',
        'status_id',
        'created_at',
    ];

    const INDEX = [
        'user.locality.province',
        'status',
        'categories.category.parent_category',
        'files',
        'files_doc',
        'questions_answer.user',
        'publication_qualifications',
        'seller_qualifications',
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
        'user.locality.province',
        'status',
        'categories.category.parent_category',
        'files',
        'files_doc',
        'questions_answer.user',
        'publication_qualifications',
        'seller_qualifications',
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
        return $this->hasMany(PublicationFile::class, 'publication_id', 'id')->where('file_type', 'img');
    }

    public function files_doc()
    {
        return $this->hasMany(PublicationFile::class, 'publication_id', 'id')->where('file_type', 'doc');
    }

    public function publication_qualifications()
    {
        return $this->hasMany(PublicationQualification::class, 'publication_id', 'id');
    }

    public function seller_qualifications()
    {
        return $this->hasMany(SellerQualification::class, 'seller_id', 'user_id');
    }

    public function questions_answer()
    {
        return $this->hasMany(PublicationQuestionAnswer::class, 'publication_id', 'id')->orderBy('id', 'DESC');
    }
}
