<div id="form-treatments">
	<h1>Online timebestilling</h1>
	<h2>Velg ønsket behandling</h2>
	<div class="accordion" id="select-treatment">
		<?php foreach ($treatments as $treatment): ?>
			<div class="accordion-item" id="<?= $treatment->ID ?>">
				<h2 class="accordion-header" id="header-<?= $treatment->ID ?>">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#description-<?= $treatment->ID ?>" aria-expanded="false" aria-controls="description-<?= $treatment->ID ?>">
						<?= $treatment->Name ?>
					</button>
				</h2>
				<div class="accordion-collapse collapse" id="description-<?= $treatment->ID ?>" data-bs-parent="#select-treatment">
					<div class="accordion-body">
						<p><?= $treatment->Description ?></p>
						<?php if (isset($treatment->Duration)): ?>
							<p><small>Varighet: <?= $treatment->Duration ?> minutter</small></p>
						<?php endif; ?>
						<p><a class="custom-button" href="#form-clinicians">Velg behandler <i class="fa fa-arrow-down"></i></a></p>
						<?php foreach ($treatment as $key => $value): ?>
							<input type="hidden" name="treatment[<?= $key ?>]"  value="<?= $value ?>">
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<div id="form-clinicians">
</div>
<div id="form-hours">
</div>
<div id="form-booking">
</div>
<div id="form-fallback">
</div>