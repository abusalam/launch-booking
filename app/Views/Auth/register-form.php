<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
	<div class="row">
		<div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1">

			<div class="card">
				<h2 class="card-header"><?=lang('Auth.register')?></h2>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>

					<form action="<?= base_url(route_to('register')) ?>" method="post">
						<?= csrf_field() ?>

						<div class="form-group">
							<label for="email"><?=lang('Auth.email')?></label>
							<input type="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
										name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>" value="<?= old('email') ?>">
							<small id="emailHelp" class="form-text text-muted"><?=lang('Auth.weNeverShare')?></small>
						</div>

						<div class="form-group">
							<label for="username"><?=lang('Auth.username')?></label>
							<input type="text" class="form-control <?php if(session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?=lang('Auth.username')?>" value="<?= old('username') ?>">
						</div>

						<div class="form-group">
							<label for="password"><?=lang('Auth.password')?></label>
							<input type="password" name="password" class="form-control <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>" autocomplete="off">
						</div>

						<div class="form-group">
							<label for="pass_confirm"><?=lang('Auth.repeatPassword')?></label>
							<input type="password" name="pass_confirm" class="form-control <?php if(session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.repeatPassword')?>" autocomplete="off">
						</div>

						<br>

						<button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.register')?></button>
					</form>

					<hr>

					<p><?=lang('Auth.alreadyRegistered')?> <a href="<?= base_url(route_to('login')) ?>"><?=lang('Auth.signIn')?></a></p>
				</div>
			</div>

		</div>
	</div>
</div>
<?= $this->endSection() ?>
