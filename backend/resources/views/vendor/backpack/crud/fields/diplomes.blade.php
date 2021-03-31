<div class="form-group col-md-12" id="diplomes">
	<input type="hidden" id="deletediplomas" name="diplomes[deletediplomas]" value=""/>
	<label>{!! $field['label'] !!}</label>
	@foreach($field['user_diplomes'] as $key => $user_diplome)
		<div data-id='{{$user_diplome->id}}' class="form-group col-md-12 diplometemplate" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px;">
			<p class="btn btn-danger deletediploma" style="float: right;">
				<i class="fa fa-times" aria-hidden="true"></i>
				Supprimer ce diplôme
			</p>
			<div class="clearfix"></div>
			<label for="diploma_id">Sélectionner votre diplôme</label>
			<select name="diplomes[diploma_id][]" class="form-control" style="margin-bottom: 15px;">
				@foreach ($field['listes_diplomes'] as $diplome)
					<option value="{{$diplome->id}}" {{($user_diplome->diploma_id == $diplome->id) ? 'selected' : ''}} >{{$diplome->title}}</option>
				@endforeach
			</select>
			<label>Spécialité</label>
    		<input type="text" name="diplomes[speciality][]" value="{{$user_diplome->speciality}}" class="form-control" style="margin-bottom: 15px;">
    		<label>Etablissement</label>
    		<input type="text" name="diplomes[school][]" value="{{$user_diplome->school}}" class="form-control" style="margin-bottom: 15px;">
    		<label for="year">Année</label>
    		<input type="number" name="diplomes[year][]" value="{{$user_diplome->year}}" class="form-control">
    		<input type="hidden" name="diplomes[id][]" value="{{$user_diplome->id}}" class="form-control">
		</div>
	@endforeach
</div>
<div class="form-group col-md-12">
	<p id="adddiploma" class="btn btn-default" style="float: right;">
		<i class="fa fa-plus" aria-hidden="true"></i>
		Ajouter un diplôme
	</p>
</div>


<script type="text/javascript">
	$( document ).ready(function() {
		var deletediplomes = [];
		var $order = 0;
		$('#adddiploma').click(function() {
			$('.diplometemplate').clone().appendTo("#diplomes");
			$('#diplomes > .diplometemplate').addClass("diplome");
			$('#diplomes > .diplometemplate').removeClass("diplometemplate");
			$('#diplomes > .diplome').css('display', 'block');

		});

		$("body").on('click', '.deletediploma', function() {
			var $id = $( this ).parent().data('id');
			if($id != 'new') {
				deletediplomes.push($id);
				$('#deletediplomas').val(JSON.stringify(deletediplomes));
			}
			$( this ).parent().remove();
		});
	});
</script>

<div data-id='new' class="form-group col-md-12 diplometemplate" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px; display: none;">
	<p class="btn btn-danger deletediploma" style="float: right;">
		<i class="fa fa-times" aria-hidden="true"></i>
		Supprimer ce diplôme
	</p>
	<div class="clearfix"></div>
	<label for="diploma_id">Sélectionner votre diplôme</label>
	<select name="diplomes[diploma_id][]" class="form-control" style="margin-bottom: 15px;">
		@foreach ($field['listes_diplomes'] as $diplome)
			<option value="{{$diplome->id}}">{{$diplome->title}}</option>
		@endforeach
	</select>
	<label>Spécialité</label>
    <input type="text" name="diplomes[speciality][]" value="" class="form-control" style="margin-bottom: 15px;">
    <label>Etablissement</label>
    <input type="text" name="diplomes[school][]" value="" class="form-control" style="margin-bottom: 15px;">
    <label for="year">Année</label>
    <input type="number" name="diplomes[year][]" id="year" value="" class="form-control">
    <input type="hidden" name="diplomes[id][]" value="new" class="form-control">
</div>