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

						<?= form_open_multipart(base_url(route_to('create-booking'))) ?>

							<fieldset <?php // session('has_no_profile') ? '' : 'disabled="disabled"'?>>
								<pre><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-6 <?php if(session('errors.passenger')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="passenger" class="required">
													<?=lang('app.booking.name')?>
												</label>
												<input type="text" class="form-control" id="passenger" required="required"
														name="passenger" placeholder="<?=lang('app.booking.name')?>"
														value="<?=old('passenger', $booking->passenger) ?>">
											</div>
										</div>
										<div class="col-md-6 <?php if(session('errors.mobile')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="mobile" class="required"><?=lang('app.booking.mobile')?></label>
												<input type="text" class="form-control" id="mobile" required="required"
															name="mobile" placeholder="<?=lang('app.booking.mobile')?>"
															value="<?=old('mobile', $booking->mobile) ?>">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group <?php if(session('errors.address')) : ?>is-invalid<?php endif ?>">
									<label for="address" class="required"><?=lang('app.booking.address')?></label>
									<input type="text" class="form-control" id="address" required="required"
											name="address" placeholder="<?=lang('app.booking.address')?>"
											value="<?=old('address', $booking->address) ?>">
								</div>
								<div class="form-group <?php if(session('errors.purpose')) : ?>is-invalid<?php endif ?>">
									<label for="purpose" class="required"><?=lang('app.booking.purpose')?></label>
									<input type="text" class="form-control" id="purpose" required="required"
											name="purpose" placeholder="<?=lang('app.booking.purpose')?>"
											value="<?=old('purpose', $booking->purpose) ?>">
								</div>
								<div>
									<div class="form-row row-eq-spacing-md">
										<div class="col-md-4 <?php if(session('errors.date')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="date" class="required"><?=lang('app.booking.date')?></label>
												<input type="text" class="form-control" id="date" required="required"
															name="date" placeholder="<?=lang('app.booking.date')?>"
															value="<?=old('date', $booking->booking_date) ?>">
											</div>
										</div>
										<div class="col-md-4 <?php if(session('errors.startTime')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="startTime" class="required"><?=lang('app.booking.startTime')?></label>
												<select class="form-control" id="startTime" name="startTime" required="required">
													<option value="" selected="selected" disabled="disabled">
														<?=lang('app.booking.firstSlot')?>
													</option>
													<option value="08:00">08:00 AM</option>
													<option value="08:30">08:30 AM</option>
													<option value="09:00">09:00 AM</option>
													<option value="09:30">09:30 AM</option>
													<option value="10:00">10:00 AM</option>
													<option value="" disabled="disabled">
														<?=lang('app.booking.nextSlot')?>
													</option>
													<option value="16:00">04:00 PM</option>
													<option value="16:30">04:30 PM</option>
													<option value="17:00">05:00 PM</option>
													<option value="17:30">05:30 PM</option>
													<option value="18:00">06:00 PM</option>
												</select>
											</div>
										</div>
										<div class="col-md-4 <?php if(session('errors.hours')) : ?>is-invalid<?php endif ?>">
											<div class="form-group">
												<label for="hours" class="required"><?=lang('app.booking.hours')?></label>
												<select class="form-control" id="hours" name="hours" required="required">
													<option value="" selected="selected" disabled="disabled">
														<?=lang('app.booking.hours')?>
													</option>
													<option value="2">2 Hours</option>
													<option value="3">3 Hours</option>
													<option value="4">4 Hours</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<pre id="debug"><?php //var_dump($booking->getTimeSlots() ?? '')?></pre>
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

		$( "#date" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true,
			changeMonth: true,
			minDate: 0, 
			maxDate: "+2M",
			dateFormat: 'dd/mm/yy',
			onSelect: function(date, ui){
				$.ajax({
						method: "POST",
						url: "<?=base_url(route_to('check'))?>",
						headers: {'X-Requested-With': 'XMLHttpRequest'},
						data: {
							 'date': date,
							 //'csrf_test_name' : $("[name='csrf_test_name']").val(),
						 },
					}).done(function(resp){
						//$("#debug").text(resp);
					});
				
			},
    });
	  
	} );
	</script>

<?= $this->endSection() ?>
