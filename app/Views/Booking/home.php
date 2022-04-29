<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1">
				<div class="card">
					<h1 class="card-title"><?=lang('app.home.welcome')?></h1>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<h4>Contact Us</h4>
						<p>Coordinator Contact No: _____________  E-Mail ID: _____________</p>
						<p>Boat Driver Contact No: _____________</p>
						<h4>Terms &amp; Conditions</h4>
						<ul>
							<li>Travelling Hours: 8AM to 12PM and 4PM to 8PM. Booking can be done for minimum 2 hours.</li>
							<li>No. of Passengers should not exceed ______ </li>
							<li>List of Passengers must be uploaded at the time of booking.</li>
						</ul>
						<h4>Do's &amp; Don'ts for Passenger/Travelers</h4>
						<ul>
							<li><strong>Do's</strong>
								<ul>
									<li>Board the vessel in proper manner.</li>
									<li>Listen to the crew.</li>
									<li>Maintain cleanliness of the boat.</li>
									<li>Maintain personal safety.</li>
								</ul>
							</li>
							<li><strong>Don'ts</strong>
							<ul>
								<li>Don't rush into the boat.</li>
								<li>Don’t stand/lean dangerously near railing.</li>
								<li>Don't cross the designated zone on the boat.</li>
								<li>Don't disturb the crew while they are operating.</li>
								<li>Don’t consume alcohol or any such substance onboard.</li>
								<li>Don’t board the vessel on drunken condition.</li>
								<li>Don’t throw garbage into the river.</li>
								<li>Don’t carry any kind of inflammable item.</li>
							</ul>
						</li>
						<h4>Location Map:</h4>
						<div><iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d9649.08181921498!2d88.12935670480154!3d25.05707846056062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x39fa54fdf522b391%3A0xbbd7a787049ba996!2sAsia!3m2!1d24.8989145!2d88.09476629999999!5e0!3m2!1sen!2sin!4v1647344907878!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
					</ul>
						<h4>Cancel &amp; refund Policy:</h4>
						<ul>
							<li><strong>Cancellation procedure:</strong>
								<ul>
									<li>Send mail to the Co-ordinator at ____________.</li>
									<li>State the reason for cancellation</li>
								</ul>
							</li>
							<li><strong>Refund Policy:</strong>
								<ul>
									<li>Beyond 24 hours from Journey time => No charge.</li>
									<li>Beyond 12 hours from journey time => 80% refund</li>
									<li>Beyond 06 hours from journey from journey time => 50% refund</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection() ?>
