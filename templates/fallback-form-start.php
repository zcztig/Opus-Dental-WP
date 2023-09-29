<div id="fallback-treatments">
    <h2>Velg behandling</h2>
    <?php $fallback_treatments = get_option('opus_fallback_treatments'); ?>
    <div class="accordion">
        <?php foreach ($fallback_treatments as $key => $treatment): ?>
            <div class="accordion-item" id="fallback-treatment-<?= $key ?>">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed">
                        <?= $treatment ?>
                    </button>
                </h2>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div id="fallback-clinicians"></div>
<div id="fallback-dayselect"></div>
<div id="fallback-timeselect"></div>
<div id="fallback-form-outer"></div>