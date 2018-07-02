@extends('layouts.app')

@section('content')
    <div class="row">
    	<div class="col-sm-12">
	    	<form >
	    		<fieldset>
		    		<legend>Documento:</legend>
		    		<div class="form-group">
			            <label for="exampleInputFile"><i class="fas fa-link" style="color: #a9a9a9"></i>

 BLEU</label>
		          	</div>
	    		</fieldset>
	    		<fieldset>
	    			<legend>Contexto:</legend>
					<div class="form-group">
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label style="background-color: #f9f4b8" for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        	<label for="exampleInputFile">Esto permite una mayor robustez a la medida</label>
			        </div>
	    		</fieldset>
	    		<fieldset>
		    		<legend>Oración a traducir:</legend>
		    		<div class="form-group">
			            <label for="exampleInputFile">"Esto permite una mayor robustez a la medida"</label>
		          	</div>
	    		</fieldset>
	    		<fieldset>
		    		<legend>Ingresar traducción:</legend>
		    		<div class="form-group">
			            <input type="text" class="form-control" placeholder="Default input" id="inputDefault">
		          	</div>
	    		</fieldset>
	    		<div class="modal-footer" style="border-top: 0;">
                  <button type="button" class="btn btn-primary">Otra oracion</button>
                  <button type="button" class="btn btn-secondary">Guardar</button>
                </div>
	    	</form>
    	</div>
    </div>
	@endsection
