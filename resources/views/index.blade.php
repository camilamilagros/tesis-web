@extends('layouts.app')

@section('content')
    <div class="row">
    	<div class="col-sm-12">
	    	<form method="post" action="{{ url('/sentences/translate') }}">
	    		{{ csrf_field() }}
	    		<fieldset>
		    		<legend>Documento:</legend>
		    		<div class="form-group">
			            <label for="exampleInputFile">
			            	<i class="fas fa-link" style="color: #a9a9a9"></i>
			            	{{ $document->title }}
			            </label>
		          	</div>
	    		</fieldset>
	    		<fieldset>
	    			<legend>Contexto:</legend>
					<div class="form-group">
			        	<label style="text-align: justify;" for="exampleInputFile">
			        	@foreach ($before as $sentence)
						    {{ $sentence->text }}
						@endforeach
			        	<span style="background-color: #f9f4b8" for="exampleInputFile">
			        		{{ $selected->text }}
			        	</span>
			        	@foreach ($after as $sentence)
						    {{ $sentence->text }}
						@endforeach
						</label>
			        </div>
	    		</fieldset>
	    		<fieldset>
		    		<legend>Oración a traducir:</legend>
		    		<div class="form-group">
			            <label for="exampleInputFile">{{ '"' . $selected->text . '"' }}</label>
		          	</div>
	    		</fieldset>
	    		<fieldset>
		    		<legend>Ingresar traducción:</legend>
		    		<div class="form-group">
			            <input type="text" class="form-control" placeholder="" id="inputDefault" name="translation">
			            <input type="hidden" value="{{ $selected->id }}" name="id">
		          	</div>
	    		</fieldset>
	    		<div class="modal-footer" style="border-top: 0;">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
	    	</form>
    	</div>
    </div>
	@endsection
