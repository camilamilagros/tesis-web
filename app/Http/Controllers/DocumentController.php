<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Sentence;

class DocumentController extends Controller
{
	public function index()
	{
		$documents = Document::orderBy('title')->paginate(10);

		return view('document.index', compact('documents'));
	}


    public function show(Document $document)
    {
    	$sentences = $document->sentences()->paginate(10);
    	return view('document.show', compact('document', 'sentences'));
    }
}
