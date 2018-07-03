<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $append = ['translation_percent'];


    public function sentences()
    {
        return $this->hasMany('App\Models\Sentence', 'docid');
    }

    public function getTranslationPercentAttribute()
    {
    	return 100 * $this->sentences()->whereNotNull('translation')->count() / $this->sentences->count();
    }
}
