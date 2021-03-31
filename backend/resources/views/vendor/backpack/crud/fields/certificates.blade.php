<div class="form-group col-md-12" id="certifs">
	<input type="hidden" id="deletedcertifs" name="certifs[deletedcertifs]" value=""/>
	<label>{!! $field['label'] !!}</label>
	@foreach($field['user_certifs'] as $key => $user_certif)
		<div data-id='{{$user_certif->id}}' class="form-group col-md-12 certif" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px;">
			<p class="btn btn-danger deletecertif" style="float: right;">
				<i class="fa fa-times" aria-hidden="true"></i>
				Supprimer cette certification
			</p>
			<div class="clearfix"></div>
			<label>Titre</label>
    		<input type="text" name="certifs[title][]" value="{{$user_certif->title}}" class="form-control" style="margin-bottom: 15px;">
    		<label>La Certification</label>
    		@if(!empty($user_certif->certif))
    		<div class="well well-sm" id="certif_file_container">
    			<a target="_blank" href="{{url($user_certif->certif)}}">
        			{{$user_certif->certif}}
    			</a>
   				<a id="certif_file_clear_button" href="#" class="btn btn-default btn-xs pull-right" title="Clear file"><i class="fa fa-remove"></i></a>
    			<div class="clearfix"></div>
			</div>
			<input type="file" id="certif_file_input" name="certifs[certif][]" value="{{$user_certif->certif}}" class="form-control hidden">
    		@else
    		<input type="file" name="certifs[certif][]" value="{{$user_certif->certif}}" class="form-control" style="margin-bottom: 15px;">
    		@endif
    		<input type="hidden" name="certifs[id][]" value="{{$user_certif->id}}" class="form-control">
		</div>
	@endforeach
</div>
<div class="form-group col-md-12">
	<p id="addcertif" class="btn btn-default" style="float: right;">
		<i class="fa fa-plus" aria-hidden="true"></i>
		Ajouter une certification
	</p>
</div>


<script type="text/javascript">
	$( document ).ready(function() {
		var deletediplomes = [];
		var deletecertifs = [];

		$('#addcertif').click(function() {
			$('.certiftemplate').clone().appendTo("#certifs");
			$('#certifs > .certiftemplate').addClass("certif");
			$('#certifs > .certiftemplate').removeClass("certiftemplate");
			$('#certifs > .certif').css('display', 'block');
		});

		$("body").on('click', '#certif_file_clear_button', function(event) {
			event.preventDefault();
			$('#certif_file_input').removeClass("hidden");
			$('#certif_file_input').attr("value", '');
			$('#certif_file_container').remove();
		});

		$("body").on('click', '.deletecertif', function() {
			var $id = $( this ).parent().data('id');
			if($id != 'new') {
				deletecertifs.push($id);
				$('#deletedcertifs').val(JSON.stringify(deletecertifs));
			}
			$( this ).parent().remove();
		});
	});
</script>

<div data-id='new' class="form-group col-md-12 certiftemplate" style="border: 1px solid #d2d6de; border-radius: 10px; padding-top: 10px; padding-bottom: 15px; display: none">
	<p class="btn btn-danger deletecertif" style="float: right;">
		<i class="fa fa-times" aria-hidden="true"></i>
		Supprimer cette certification
	</p>
	<div class="clearfix"></div>
	<label>Titre</label>
	<input type="text" name="certifs[title][]" class="form-control" style="margin-bottom: 15px;">
	<label>La Certification</label>
	<input type="file" name="certifs[certif][]" class="form-control" style="margin-bottom: 15px;">
	<input type="hidden" name="certifs[id][]" value="new" class="form-control">
</div>