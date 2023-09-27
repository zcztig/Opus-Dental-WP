<h2>Velg behandler</h2>
<div class="accordion" id="select-clinician" data-treatment="<?= $formdata['treatment']['ID'] ?>">
  <?php foreach ($clinicians as $clinician): ?>
  <div class="accordion-item" id="<?= $clinician->ID ?>">
    <h2 class="accordion-header" id="header-<?= $clinician->ID ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#description-<?= $clinician->ID ?>"
        aria-expanded="false"
        aria-controls="description-<?= $clinician->ID ?>">
        <?= $clinician->Title ?> <?= $clinician->Name ?>
      </button>
    </h2>
    <div class="accordion-collapse collapse"
      id="description-<?= $clinician->ID ?>"
      data-bs-parent="#select-clinician">
      <div class="accordion-body">
        <div class="opus-grid">
          <div class="col-3">
            <p>Du kan bestille time for <em><?= $formdata['treatment']['Name'] ?></em> hos <?= $clinician->Name ?>.</p>
            <p><a href="#form-hours">Finn ledig time i kalenderen her</a> <a href="#form-hours" class="custom-button"><i class="fa fa-calendar"></i></a></p>
          </div>
          <div class="col">
            <?php if (filter_var($clinician->Info, FILTER_VALIDATE_URL)): ?>
              <img src="<?= $clinician->Info ?>" style="max-width: 150px">
            <?php endif; ?>
          </div>
        </div>
        <?php foreach ($clinician as $key => $value): ?>
        <input type="hidden" name="clinician[<?= $key ?>]"
          value="<?= $value ?>">
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php /* gammel kode
 <h2>Online timebestilling</h2>
<p>Du har gjort følgende valg:</p>
<ul>
  <li>Behandling, <?= $_POST['clinicianName'] ?></li>
</ul>
<h4>Steg 2 -Velg behandler:</h4>
<div class="clinicians table-listings">
  <?php foreach ($clinicians as $clinician): ?>
    <div class="table-listing">
      <div class="opus-grid mobile" style="align-items: center">
        <div class="col-3">
          <h5><?= $clinician->Name ?></h5>
          <p><?= $clinician->Title ?></p>
        </div>
        <div class="col" style="text-align: right;">
          <img src="<?= $clinician->Info ?>" style="max-width: 100%">
        </div>
      </div>
      <input type="hidden" name="clinician-id" value="<?= $clinician->ID ?>">
      <input type="hidden" name="clinician-name" value="<?= $clinician->Name ?>">
      <input type="hidden" name="clinician-id" value="<?= $_POST['clinician'] ?>">
      <input type="hidden" name="clinician-name" value="<?= $_POST['clinicianName'] ?>">
      <input type="hidden" name="clinician-duration" value="<?= $_POST['duration'] ?>">
      <input type="hidden" name="ClinicID" value="<?= $clinician->ClinicID ?>">
    </div>
  <?php endforeach; ?>
</div>
 */ ?>