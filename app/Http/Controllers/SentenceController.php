<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentence;

class SentenceController extends Controller
{
    function index()
    {
    	$data = [];
    	return view('index',$data);
    }

    function toTranslate()
    {
    	dd(Sentence::selectWithActiveLearning());
    }
}
