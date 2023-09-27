<h2>Hvilket tidspunkt passer best?</h2>
<div class="btn-group">
   <input name="opus-select-time[]" value="Morgen" type="checkbox" class="btn-check" id="opus-select-time-morning">
   <label for="opus-select-time-morning" class="btn btn-outline-dark">Morgen</label>
   <input name="opus-select-time[]" value="Formiddag" type="checkbox" class="btn-check" id="opus-select-time-daytime">
   <label for="opus-select-time-daytime" class="btn btn-outline-dark">Formiddag</label>
   <input name="opus-select-time[]" value="Ettermiddag" type="checkbox" class="btn-check" id="opus-select-time-afternoon">
   <label for="opus-select-time-afternoon" class="btn btn-outline-dark">Ettermiddag</label>
   <input name="opus-select-time[]" value="Kveld" type="checkbox" class="btn-check" id="opus-select-time-evening">
   <label for="opus-select-time-evening" class="btn btn-outline-dark">Kveld</label>
</div>
<p><em>Du kan gå videre til neste steg uten å velge dag og tidspunkt, da setter vi deg opp på første ledige time.</em></p>
<p>Hvis timen vi setter deg opp på allikvel ikke passer må du kontakte oss så raskt som mulig for å booke om.</p>
<input type="hidden" name="fallback-clinician" value="<?= $_POST['clinician'] ?>">
<input type="button" class="btn btn-outline-dark btn-lg" id="opus-fallback-get-form" value="Gå videre">