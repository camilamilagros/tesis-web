<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentence;

class SentenceController extends Controller
{
    function index()
    {
    	dd(Sentence::all());
    }

    function toTranslate()
    {
    	dd(Sentence::selectWithActiveLearning());
    }
}
