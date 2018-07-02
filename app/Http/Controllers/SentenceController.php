<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentence;

class SentenceController extends Controller
{
    function index()
    {
    	$data = [];
    	return view('index', $data);
    }

    function toTranslate()
    {
        $sentence = Sentence::whereNull('translation')
            ->orderBy('score', 'desc')->first();
        $document = $sentence->document;

    	$data = [
    		'document' => $document,
            'before' => $document->sentences()
                ->where('id','<', $sentence->id)
                ->orderBy('id', 'desc')
                ->take(4)
                ->get()
                ->reverse(),
            'selected' => $sentence,
            'after' => $document->sentences()
                ->where('id','>', $sentence->id)
                ->take(4)
                ->get()
    	];

        return view('index', $data);
    }

    function translate(Request $request)
    {
        Sentence::find($request->id)
            ->update(['translation' => $request->translation]);

        return redirect('/sentences/translate')
            ->with('success', 'Se guarda la traduccion con exito!! c:');
    }
}
