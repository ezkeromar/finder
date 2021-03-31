{{ $selected = '' }}
<div class="form-group col-md-12" id="experiences">
	<label>{!! $field['label'] !!}</label>
	<input type="hidden" id="deletedexperience" name="experiences[deletedexperience]" value=""/>
	@foreach($field['user_experiences'] as $key => $user_experience)
		<div data-id="{{$user_experience->id}}" class="form-group col-md-12 experience" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px;">
			<p class="btn btn-danger deleteexperience" style="float: right;">
				<i class="fa fa-times" aria-hidden="true"></i>
				Supprimer cet expérience
			</p>
			<div class="clearfix"></div>
			
    		<label>Titre du poste</label>
		    <input type="text" value="{{$user_experience->title}}" name="experiences[title][]" class="form-control">

			<label>Description</label>
		    <textarea name="experiences[description][]" class="form-control" style="margin-bottom: 15px;">{{$user_experience->description}}</textarea>
				
			<label>Date de début</label>
		    <input type="date" class="form-control" value="{{$user_experience->date_start}}" name="experiences[date_start][]" id="experiences[date_start][]">

		    <label>Date de fin</label>    
		    <input type="date" class="form-control" value="{{$user_experience->date_end}}" name="experiences[date_end][]" id="experiences[date_end][]">

		    <label>TJM</label>
    		<input type="number" name="experiences[tjm][]" value="{{$user_experience->tjm}}" class="form-control">

		    <label>Entreprise</label>
		    <input type="text" value="{{$user_experience->client}}" name="experiences[client][]" class="form-control">

		    <label>Technologies</label>
		    <select multiple style="height: 70px;" class="form-control technologies_select" name="experiences[technologies][{{$key}}][]">
		    	@if(empty($user_experience->technology))
			    	<option value="*" selected>*</option>
				@endif
		    	@foreach($field['technologies'] as $technology)
		    		{{$selected = ''}}
		    		@foreach($user_experience->technology as $t)
		    			@if($technology->id == $t->id)
		    				{{$selected = 'selected'}}
		    			@endif
		    		@endforeach
		    		<option value="{{$technology->id}}" {{$selected}}>{{$technology->title}}</option>
		    	@endforeach
		    </select>

		    <label>Compétances</label>
		    <select multiple style="height: 70px;" class="form-control competances_select" name="experiences[competances][{{$key}}][]">
		    	@if(empty($user_experience->skill))
			    	<option value="*" selected>*</option>
				@endif
		    	@foreach($field['competances'] as $competance)
		    		{{$selected = ''}}
		    		@foreach($user_experience->skill as $skill)
		    			@if($skill->id == $competance->id)
		    				{{$selected = 'selected'}}
		    			@endif
		    		@endforeach
		    		<option value="{{$competance->id}}" {{$selected}}>{{$competance->title}}</option>
		    	@endforeach
		    </select>

		    <input type="hidden" value="{{$user_experience->id}}" name="experiences[id][]" class="form-control">
		    <input type="hidden" value="{{$key}}" id="last_used_id">
		</div> 
	@endforeach
</div>
<div class="form-group col-md-12">
	<p id="addexperience" class="btn btn-default" style="float: right;">
		<i class="fa fa-plus" aria-hidden="true"></i>
		Ajouter une expérience
	</p>
</div>

<style type="text/css">
	.select2-selection__choice {
		background-color: transparent !important;
		color: #000 !important;
	}
</style>

<script type="text/javascript">
	$( document ).ready(function() {
		$('.technologies_select').select2();
		$('.competances_select').select2();
		var $order = $('#last_used_id').val();
		if($order == undefined)
			$order = 0;
		var deletedexperiece = [];
		$('#addexperience').click(function() {
			$(".competances_select").select2("destroy");
			$(".technologies_select").select2("destroy");
			$('.experiencetemplate').clone().appendTo("#experiences");
			$('#experiences > .experiencetemplate > .technologies_select').attr('name', 'experiences[technologies]['+$order+'][]');
			$('#experiences > .experiencetemplate > .competances_select').attr('name', 'experiences[competances]['+$order+'][]');
			$('#experiences > .experiencetemplate').addClass("experience");
			$('#experiences > .experiencetemplate').removeClass("experiencetemplate");
			$('#experiences > .experience').css('display', 'block');
			$('.technologies_select').select2();
			$('.competances_select').select2();
			$order = parseInt($order) + 1;
		});

		$("body").on('click', '.deleteexperience', function() {
			var $id = $( this ).parent().data('id');
			if($id != 'new') {
				deletedexperiece.push($id);
				$('#deletedexperience').val(JSON.stringify(deletedexperiece));
			}
			$( this ).parent().remove();
		});
	});
</script>

<div class="form-group col-md-12 experiencetemplate" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px; display: none;">
	<p class="btn btn-danger deleteexperience" style="float: right;">
		<i class="fa fa-times" aria-hidden="true"></i>
		Supprimer cet expérience
	</p>
	<div class="clearfix"></div>

	<label for="year">Titre du poste</label>
    <input type="text" name="experiences[title][]" id="year" value="" class="form-control">

	<label>Description</label>
    <textarea name="experiences[description][]" class="form-control" style="margin-bottom: 15px;"></textarea>
		
	<label>Date de début</label>
    <input type="date" class="form-control" name="experiences[date_start][]" id="experiences[date_start][]">

    <label>Date de fin</label>    
    <input type="date" class="form-control" name="experiences[date_end][]" id="experiences[date_end][]">

    <label>TJM</label>
    <input type="number" name="experiences[tjm][]" class="form-control">

    <label for="year">Entreprise</label>
    <input type="text" name="experiences[client][]" id="year" value="" class="form-control">

    <label>Technologies</label>
    <select multiple style="height: 70px; width: 100%;" class="form-control technologies_select" name="experiences[technologies][new][]">
    	<option value="*" selected>*</option>
    	@foreach($field['technologies'] as $technology)
    		<option value="{{$technology->id}}">{{$technology->title}}</option>
    	@endforeach
    </select>

    <label>Compétances</label>
    <select multiple style="height: 70px; width: 100%;" class="form-control competances_select" name="experiences[competances][new][]">
    	<option value="*" selected>*</option>
    	@foreach($field['competances'] as $competance)
    		<option value="{{$competance->id}}">{{$competance->title}}</option>
    	@endforeach
    </select>

    <input type="hidden" name="experiences[id][]" value="new" class="form-control">
</div>

