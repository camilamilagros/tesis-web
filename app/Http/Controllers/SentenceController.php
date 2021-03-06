<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sentence;

class SentenceController extends Controller
{
    function createTranslation(Sentence $sentence)
    {
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

        return view('sentence.create_translation', $data);
    }

    function storeTranslation(Request $request)
    {
        $sentence = Sentence::find($request->id);
        $sentence->update(['translation' => $request->translation]);

        return redirect('/document/' . $sentence->docid)
            ->with('success', 'Se guarda la traduccion con exito!! c:');
    }
}
