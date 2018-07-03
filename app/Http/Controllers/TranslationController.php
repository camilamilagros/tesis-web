<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sentence;

class TranslationController extends Controller
{
    function create()
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

        return view('translation.create', $data);
    }

    function store(Request $request)
    {
        Sentence::find($request->id)
            ->update(['translation' => $request->translation]);

        return redirect('/translation')
            ->with('success', 'Se guarda la traduccion con exito!! c:');
    }
}
