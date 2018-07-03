@extends('layouts.app')

@section('content')
    <div class="row">
    	<div class="col-sm-12">
    		<h2>Documentos</h2>
    		<div class="table-responsive">
	    		<table class="table table-hover">
				  <thead>
				    <tr>
				      <th style="width: 40%;" scope="col">Titulo</th>
				      <th style="width: 5%;" scope="col">Oraciones</th>
				      <th style="width: 35%;" scope="col">% Traducción</th>
				      <th style="width: 20%;" scope="col">Acciones</th>
				    </tr>
				  </thead>
				  <tbody>
				    @foreach ($documents as $document)
				    <tr>
				      <td>{{ $document->title }}</td>
				      <td>{{ $document->sentences->count() }}</td>
				      <td>
				      	<div class="progress">
						  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="1" style="{{ 'width: ' . $document->translation_percent . '%' }}"></div>
						</div>
				      </td>
				      <td st>
				      	<a href="{{ url('/document/' . $document->id) }}">
			            	<i class="far fa-eye fa-2x"></i>
			            </a>
				      </td>
				    </tr>
				    @endforeach
				  </tbody>
				</table>
				<div class="text-center">
				  <ul class="pagination">
				    <li class="page-item disabled">
				      <a class="page-link" href="{{ $documents->previousPageUrl() }}">&laquo;</a>
				    </li>
				    @for ($i = 1; $i <= $documents->count(); $i++)
				    	@if ($documents->currentPage() == $i)
				    		<li class="page-item active">
						@else
						    <li class="page-item">
				    	@endif
						      <a class="page-link" href="{{ $documents->url($i)}}">{{ $i }}</a>
						    </li>
				    @endfor
				    <li class="page-item">
				      <a class="page-link" href="{{ $documents->nextPageUrl() }}">&raquo;</a>
				    </li>
				  </ul>
				</div>

			</div>
    	</div>
    </div>
	@endsection
