
<a href="#" id="add_review_toggle">Add a new Review</a>
<div class="review_add_form_wrapper">

	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>

	<form action="<?php echo url::site($page_name)?>" method="post" class="review_form">
		<?php
		$ratings = array('1'=>'-1-','2'=>'-2-','3'=>'-3-','4'=>'-4-','5'=>'-5-');
		$fields = array(
			'rating' => array('Rating','select','text_req', $ratings),
			'body'	 => array('Comments','textarea','text_req', ''),
			'name'	 => array('Name','input','text_req', ''),
			'email'	 => array('Email','input','email_req', ''),
		);
		if(!isset($values)) $values = array();
		if(!isset($errors)) $errors = array();
		echo val_form::generate_fields($fields, $values, $errors);
		?>	
		<button type="submit">Submit Review</button>
	</form>
</div>

<div class="review_item" id="supa_injector" style="display:none">
	<div class="review_rating">
		<b>Rating</b> <em id="qwz_rating"></em>/5
	</div>
	<div class="review_body">
		<em id="qwz_body"></em>
	</div>
	<div class="review_name">
		- <i><em id="qwz_name"></em></i>
	</div>
</div>
	
<script type="text/javascript">
		$("#add_review_toggle").click(function() {
			$(".review_add_form_wrapper").slideToggle("fast");
		});
		$(".review_add_form_wrapper").hide();
		
		$('.review_form').ajaxForm({		 
			beforeSubmit: function(fields, form){
				if(! $("input, textarea", form[0]).jade_validate()) return false;
				$(fields).each(function() {
					$('#supa_injector em#qwz_' + this.name).replaceWith(this.value);
				});
				$('.review_add_form_wrapper').html('<div class="ajax_loading">Loading...</div>');
			},
			success: function(data) {
				$('.review_add_form_wrapper').replaceWith(data);
				// todo: this must be done only on success.
				$('#supa_injector').show();
				$('#add_review_toggle').remove();
			}
		});
</script>
