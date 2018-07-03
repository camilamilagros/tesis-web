@extends('layouts.app')

@section('content')
    <div class="row">
    	<div class="col-sm-12">
    		<h2>{{ 'Documento: ' . $document->title }}</h2>

    		<div class="table-responsive">
	    		<table class="table table-hover">
				  <thead>
				    <tr>
				      <th style="width: 45%;" scope="col">Español</th>
				      <th style="width: 45%;" scope="col">Shipibo-Konibo</th>
				      <th style="width: 10%;" scope="col">Acciones</th>
				    </tr>
				  </thead>
				  <tbody>
				    @foreach ($sentences as $sentence)
				    <tr>
				      <td>{{ $sentence->text }}</td>
				      <td>{{ $sentence->translation }}</td>
				      <td st>
				      	<a href="{{ url('/sentence/' . $sentence->id . '/translation') }}">
			            	<i class="fas fa-language fa-3x"></i>
			            </a>
				      </td>
				    </tr>
				    @endforeach
				  </tbody>
				</table>
				<div class="text-center">
				  <ul class="pagination">
				    <li class="page-item disabled">
				      <a class="page-link" href="{{ $sentences->previousPageUrl() }}">&laquo;</a>
				    </li>
				    @for ($i = 1; $i <= $sentences->count(); $i++)
				    	@if ($sentences->currentPage() == $i)
				    		<li class="page-item active">
						@else
						    <li class="page-item">
				    	@endif
						      <a class="page-link" href="{{ $sentences->url($i)}}">{{ $i }}</a>
						    </li>
				    @endfor
				    <li class="page-item">
				      <a class="page-link" href="{{ $sentences->nextPageUrl() }}">&raquo;</a>
				    </li>
				  </ul>
				</div>

			</div>
    	</div>
    </div>
	@endsection
