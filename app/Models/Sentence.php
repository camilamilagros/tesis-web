<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
	protected $fillable = ['translation'];

	public $timestamps = false;

	public function document()
    {
        return $this->belongsTo('App\Models\Document', 'docid');
    }

    public function getTextAttriute($value)
    {
    	return trim(preg_replace('/\s\s+/', ' ', $value));
    }
}
