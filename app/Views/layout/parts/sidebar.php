<div class="sidebar-menu">
	<div class="sidebar-divider"></div>
	<div class="hidden-md-and-up">
		<div class="sidebar-link">
			<?=view('layout/parts/YouTube', [], ['cache' => 3600])?>
		</div>
		<div class="sidebar-divider"></div>
	</div>
	
	<?php \helper('auth'); ?>
	<?php
	if (! function_exists('add_class'))
	{
		function add_class($routeAlias, $class = 'active')
		{
			return current_url() === base_url(route_to($routeAlias)) ? $class : '';
		}
	}
	?>

	<?php if (logged_in()) : ?>
	
		<?php if (user_id() !== '1'):?>
			<div class="sidebar-title">
				<?=session('school.school')?>
				<?=session('school.class')?>
			</div>
			<br/>
		<?php endif ?>

		<h5 class="sidebar-title font-weight-bold">
			<?=user()->full_name?>
			(<?=(user_id() === '1') ? 'Super Admin' : ((ENVIRONMENT !== 'production') ? join(',', user()->getRoles()) : '')?>)
		</h5>
		<div class="sidebar-divider"></div>
		
		<?php if (user_id() === '1'):?>
			<a href="<?= base_url(route_to('schools'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('schools')?>">
				<span class="sidebar-icon">
					<i class="fa fa-building" aria-hidden="true"></i>
				</span>
					<?=lang('app.school.listTitle')?>
			</a>
		<?php endif ?>
		<?php if(in_groups('admins')): ?>
			<a href="<?= base_url(route_to('accounts'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('accounts')?>">
				<span class="sidebar-icon">
					<i class="fa fa-users" aria-hidden="true"></i>
				</span>
				<?=lang('app.menu.userManagement')?>
			</a>
		<?php endif ?>

		<a href="<?= base_url(route_to('profile'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('profile')?>">
			<span class="sidebar-icon">
				<i class="fa fa-user" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.updateProfile')?>
		</a>
		<a href="<?= base_url(route_to('logout'))?>"
			class="sidebar-link sidebar-link-with-icon">
			<span class="sidebar-icon">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.logout')?>
		</a>
		<?php if(in_groups(['teachers', 'students'])): ?>
			<h5 class="sidebar-title font-weight-bold">
				<?=lang('app.menuAssignment.groupTitle')?>
			</h5>

			<div class="sidebar-divider"></div>
			
			<a href="<?= base_url(route_to('view-assignments'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('view-assignments')?>">
				<span class="sidebar-icon">
					<i class="fa fa-book" aria-hidden="true"></i>
				</span>
				<?=lang('app.menuAssignment.myAssignments')?>
			</a>

			<?php if(in_groups('teachers')): ?>
				<a href="<?= base_url(route_to('create-assignment'))?>"
					class="sidebar-link sidebar-link-with-icon <?= add_class('create-assignment')?>">
					<span class="sidebar-icon">
						<i class="fa fa-briefcase" aria-hidden="true"></i>
					</span>
						<?=lang('app.menuAssignment.newAssignment')?>
				</a>

				<a href="<?= base_url(route_to('create-topic'))?>"
					class="sidebar-link sidebar-link-with-icon <?= add_class('create-topic')?>">
					<span class="sidebar-icon">
						<i class="fa fa-ticket" aria-hidden="true"></i>
					</span>
						<?=lang('app.menuAssignment.NewTopic')?>
				</a>
			<?php endif ?>

			<h5 class="sidebar-title font-weight-bold">
				<?=lang('app.menuEvaluation.groupTitle')?>
			</h5>

			<div class="sidebar-divider"></div>

			<a href="<?= base_url(route_to('list-answers'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('list-answers')?>">
				<span class="sidebar-icon">
					<i class="fa fa-file-text" aria-hidden="true"></i>
				</span>
				<?=lang('app.menuEvaluation.myAnswers')?>
			</a>

			<a href="<?= base_url(route_to('view-scores'))?>"
				class="sidebar-link sidebar-link-with-icon <?= add_class('view-scores')?>">
				<span class="sidebar-icon">
					<i class="fa fa-graduation-cap" aria-hidden="true"></i>
				</span>
				<?=lang('app.menuEvaluation.myScores')?>
			</a>

		<?php endif ?>

	<?php else : ?>
		<a href="<?= base_url(route_to('home'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('home')?>">
			<span class="sidebar-icon">
				<i class="fa fa-ship" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.booking')?>
		</a>
		<a href="<?= base_url(route_to('login'))?>"
			class="sidebar-link sidebar-link-with-icon <?= add_class('login')?>">
			<span class="sidebar-icon">
				<i class="fa fa-sign-in" aria-hidden="true"></i>
			</span>
			<?=lang('app.menu.login')?>
		</a>	
	<?php endif ?>
</div>

<div class="flex-grow-1"></div> <!-- push the below items to the bottom -->

<div class="sidebar-menu">
	<div class="sidebar-link">
		<span class="badge badge-secondary badge-pill m-5">Env: <?= ENVIRONMENT ?></span>
	</div>
	<div class="sidebar-link">
		<span class="badge badge-success badge-pill m-5">Time: {elapsed_time}s</span>
	</div>
</div>