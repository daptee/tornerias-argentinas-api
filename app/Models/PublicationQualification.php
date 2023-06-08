<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationQualification extends Model
{
    use HasFactory;
    protected $table = "publications_qualifications";
    
    protected $hidden = [
        'publication_id',
        'user_id'
    ];

    protected $fillable = [
        'publication_id',
        'user_id',
        'qualification',
        'comment'
    ];

    public function publication()
    {
        return $this->hasOne(Publication::class, 'id', 'publication_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function get_all_publication_qualification($id)
    {
        return PublicationQualification::with(['publication', 'user'])->find($id);
    }
}
