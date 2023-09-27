<h2>Velg behandler</h2>
<div class="accordion">
    <input type="hidden" name="fallback-treatment" value="<?= $_POST['treatment'] ?>">
    <?php foreach ($clinicians as  $key => $clinician): ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    <?= $clinician ?>
                </button>
            </h2>
        </div>
    <?php endforeach; ?>
</div>