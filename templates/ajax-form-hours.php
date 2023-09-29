<?php /*
<h2>Online timebestilling</h2>
<p>Du har gjort følgende valg:</p>
<ul>
  <li>Behandling, <?= $_POST['treatmentName'] ?></li>
  <li>Behandler, <?= $_POST['clinicianName'] ?></li>
</ul>
<h4>Steg 3 - Velg tidspunkt:</h4>
<input type="hidden" name="treatment-id" value="<?= $_POST['treatment'] ?>">
<input type="hidden" name="treatment-name" value="<?= $_POST['treatmentName'] ?>">
<input type="hidden" name="clinician-id" value="<?= $_POST['clinician'] ?>">
<input type="hidden" name="clinician-name" value="<?= $_POST['clinicianName'] ?>">
<input type="hidden" name="clinic-id" value="<?= $_POST['clinic'] ?>">
<input type="hidden" name="treatment-duration" value="<?= $_POST['duration'] ?>">
*/ ?>
<h2>Velg dato og tid</h2>
<div class="booking-calendar white-theme">
</div>
<div class="booking-events"></div>
<div class="clear"></div>
<p>Finner du ikke et tidspunkt som passer? Ring oss gjerne på tlf 909 00 909 så finner vi ut av det sammen.</p>