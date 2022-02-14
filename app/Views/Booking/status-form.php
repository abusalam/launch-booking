<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<?php \helper('form'); ?>
	<?php \helper('html'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 offset-md-1">

				<div class="card">
					<h2 class="card-header">
						<?=$booking->getPassenger() ?? lang('app.booking.notFound')?>
						<span class="text-monospace text-primary">
							#<?=$id?>
						</span>
					</h2>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<h4><strong>Status: </strong><?=$pg_resp->order_status ?? ''?></h4>
						<p><strong><?=lang('app.booking.mobile')?>: </strong><?=$booking->getMobile() ?? ''?></p>
						<p><strong><?=lang('app.booking.address')?>: </strong><?=$booking->getAddress() ?? ''?></p>
						<p><strong><?=lang('app.booking.purpose')?>: </strong><?=$booking->getPurpose() ?? ''?></p>
						<h3>Slot: <?=$booking->getBookedSlot() ?? ''?></h3>
						<pre><?php //var_dump($pg_resp ?? '')?></pre>
						<pre><?php //var_dump(session()->get('post_data') ?? '')?></pre>
						
						<a href="<?php //$pg_resp->payment_link?>" class="btn">Print Receipt</a>
					</div>
				</div>

			</div>
		</div>
	</div>

<?= $this->endSection() ?>
