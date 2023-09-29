<div id="available-times">
  <?php foreach ($return['dates'] as $d): ?>
    <div class="table-listings" data-date="<?= $d ?>">
      <?php foreach ($return['hours'][$d] as $h): ?>
        <div class="table-listing">
          <?php $dt = new DateTime($h->Start); ?>
          <?= wp_date('j. F Y \k\l. H:i', date_timestamp_get($dt)) ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>
