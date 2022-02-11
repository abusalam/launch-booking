<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header"><?=lang('app.booking.createTitle')?></h2>
					<div class="card-body">

						<?= view('Myth\Auth\Views\_message_block') ?>

						<p><?=lang('app.booking.createHelp')?></p>

						<?= form_open_multipart(base_url(route_to('create-assignment'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($topicId ?? '')?></pre>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-6 <?php if(session('errors.questions')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="topic_id" class="required">
													<?=lang('app.booking.name')?>
												</label>
												<input type="text" class="form-control" id="title" required="required"
														name="title" placeholder="<?=lang('app.booking.name')?>"
														value="<?php // old('title', $assignment->title) ?>">
											</div>
										</div>
										<div class="col-md-6 <?php if(session('errors.questions')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="questions" class="required"><?=lang('app.booking.mobile')?></label>
												<input type="text" class="form-control" id="questions" required="required"
															name="questions" placeholder="<?=lang('app.booking.mobile')?>"
															value="<?php // old('questions', $assignment->questions) ?>">
											</div>
										</div>
									</div>
								</div>

								<div class="form-group <?php if(session('errors.title')) : ?>is-invalid<?php endif ?>">
									<label for="title" class="required"><?=lang('app.booking.address')?></label>
									<input type="text" class="form-control" id="title" required="required"
											name="title" placeholder="<?=lang('app.booking.address')?>"
											value="<?php // old('title', $assignment->title) ?>">
								</div>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-4 <?php if(session('errors.questions')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="questions" class="required"><?=lang('app.booking.date')?></label>
												<input type="text" class="form-control" id="questions" required="required"
															name="questions" placeholder="<?=lang('app.booking.date')?>"
															value="<?php // old('questions', $assignment->questions) ?>">
											</div>
										</div>
										<div class="col-md-4 <?php if(session('errors.startTime')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="startTime" class="required"><?=lang('app.booking.slotTitle')?></label>
												<select class="form-control" id="type" name="type" required="required">
												<option value="" selected="selected" disabled="disabled">
													<?=lang('app.booking.slotTitle')?>
												</option>
													<option value="1"><?=lang('app.booking.firstSlot')?></option>
													<option value="2"><?=lang('app.booking.nextSlot')?></option>
												</select>
											</div>
										</div>
										<div class="col-md-4 <?php if(session('errors.endTime')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="endTime" class="required"><?=lang('app.booking.end')?></label>
                        <span class="navbar-text text-monospace badge" id="duration"></span>
                        <div class="mt-10" id="slider-range"></div>
												<input type="hidden" class="form-control" id="endTime" required="required"
															name="endTime" placeholder="<?=lang('app.booking.end')?>"
															value="<?php // old('endTime', $assignment->endTime) ?>">
											</div>
										</div>
									</div>
								</div>
 
							</fieldset>
							<button type="submit" class="btn btn-primary btn-block form-control"><?=lang('app.booking.btnCreateTitle')?></button>
						<?=form_close()?>

					</div>
				</div>

			</div>
		</div>
	</div>

	<script {csp-script-nonce}>
	$( function() {

    var zeroPad = function (n) {
        return n < 10 ? '0' + n : n;
      };

    const timeSlot = [];
    timeSlot[1] = "08:00 AM";
    timeSlot[2] = "09:00 AM";
    timeSlot[3] = "10:00 AM";
    timeSlot[4] = "11:00 AM";
	  timeSlot[5] = "12:00 AM";

	  $( "#slider-range" ).slider({
      range: true,
      min: 1,
      max: 5,
      values: [ 2, 3 ],
      slide: function( event, ui ) {
        if(ui.values[0]==ui.values[1]) {
          ui.slider("values", 0, 2);
          ui.slider("values", 1, 4);
          ui.slider("refresh");
        } else {
          $( "#duration" ).text( timeSlot[ui.values[ 0 ]] + " - " + timeSlot[ui.values[ 1 ]] );
        }
      }
	  });
    if ($( "#slider-range" ).slider( "values", 0 ) != $( "#slider-range" ).slider( "values", 1 )) {
      $( "#duration" ).text( timeSlot[$( "#slider-range" ).slider( "values", 0 )] +
		    " - " + timeSlot[$( "#slider-range" ).slider( "values", 1 )] );
    }
	  
	} );
	</script>

<style {csp-style-nonce}>

</style>

<script {csp-script-nonce}>

// var startTimeSlider = document.getElementById('startTimeSlider');
// var startTime = document.getElementById('startTime');
// var endTimeSlider = document.getElementById('endTimeSlider');
// var endTime = document.getElementById('endTime');



// var zeroPad = function (n) {
//     return n < 10 ? '0' + n : n;
//   };

// startTimeSlider.addEventListener("click", function (event) {
//  startTime.value=timeSlot[startTimeSlider.value];
// });

// endTimeSlider.addEventListener("change", function (event) {
//   const d = new Date();
//   var start = d.toDateString()  + " " + timeSlot[startTimeSlider.value];

//   var end = Date.parse(start);
//   var endDate = new Date(end);
//   endDate.setMinutes( endDate.getMinutes() + (30 * endTimeSlider.value)  );
//   var hours = (endDate.getHours() > 12 ? endDate.getHours()-12 : endDate.getHours());
//   var ending = (endDate.getHours() >= 12 ? "PM" : "AM");
//   endTime.value=zeroPad(hours) + ":" + zeroPad(endDate.getMinutes()) + " " + ending;
// });

</script>

<?= $this->endSection() ?>
