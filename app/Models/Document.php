<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function sentences()
    {
        return $this->hasMany('App\Models\Sentence', 'docid');
    }
}
