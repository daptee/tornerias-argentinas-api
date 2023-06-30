<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PublicationQuestionAnswer extends Model
{
    use HasFactory;

    protected $table = "publications_questions_answers";

    protected $fillable = [
        "publication_id",
        "user_id",
        "ask",
        "ask_date",
        "answer",
        "answer_date"
    ];

    public function publication(): HasOne
    {
        return $this->hasOne(Publication::class, 'id', 'publication_id');
    }

}
