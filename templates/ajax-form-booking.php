<h2>Pasientinfo</h2>
<form id="booking-form" class="need-validation" novalidate>
    <input type="hidden" name="TimeSlot[Start]" value="<?= $_POST['selectedtime'] ?>">
    <?php 
    $end = date_format(
        date_add(
            date_create($_POST['selectedtime']),
            DateInterval::createFromDateString($formdata['treatment']['Duration'].' minutes')
        ),
        'Y-m-d\TH:i:s'
    );
    ?>
    <input type="hidden" name="TimeSlot[ClinicianID]" value="<?= $formdata['clinician']['ID'] ?>">
    <input type="hidden" name="TimeSlot[TreatmentID]" value="<?= $formdata['treatment']['ID'] ?>">
    <input type="hidden" name="TimeSlot[ClinicID]" value="<?= $formdata['clinician']['ClinicID'] ?>">
    <input type="hidden" name="TimeSlot[End]" value="<?= $end ?>">
    <div class="opus-grid">
        <div class="col">
            <label>Fornavn</label>
            <input name="Patient[FirstName]" type="text" class="form-control" required>
            <div class="invalid-feedback">
                Dette feltet er obligatorisk
            </div>
        </div>
        <div class="col">
            <label>Etternavn</label>
            <input name="Patient[LastName]" type="text" class="form-control" required>
            <div class="invalid-feedback">
                Dette feltet er obligatorisk
            </div>
        </div>
    </div>
    <div class="opus-grid">
        <div class="col">
            <label for="PatientEmail">E-postadresse</label>
            <input id="PatientEmail" name="Patient[Email]" type="email" class="form-control" required>
            <div class="invalid-feedback">
                Vi trenger en e-postadresse i riktig format
            </div>
        </div>
        <div class="col">
            <label>Mobilnummer</label>
            <input name="Patient[MobilePhoneNumber]" class="form-control" type="tel" id="phone" name="phone" pattern="[4|9]\d{7}$" required>
            <div class="invalid-feedback">
                Skriv mobilnummer, åtte siffer uten mellomrom
            </div>
        </div>
        <div class="col">
            <label>Fødselsnummer</label>
            <input name="Patient[PatientPersonalIdentification]"
            type="text" class="form-control" placeholder="DDMMÅÅXXXXX"
            pattern="^(0[1-9]|[1-2][0-9]|31(?!(?:0[2469]|11))|30(?!02))(0[1-9]|1[0-2])\d{7}$"
            required>
            <div class="invalid-feedback">
                Fødselsnummer består av fødslesdato i følgende format: DDMMÅÅ pluss personnummer på fem siffer uten mellomrom.
            </div>
        </div>
    </div>
    <div class="opus-grid">
        <div class="col-2">
            <label>Adresse</label>
            <input name="Patient[Adress1]" type="text" class="form-control" required>
            <div class="invalid-feedback">
                Dette feltet er obligatorisk
            </div>
        </div>
        <div class="col">
            <label>Postnummer</label>
            <input name="Patient[PostalCode]" pattern="\d*" maxlength="4" minlength="4" type="text" class="form-control" required>
            <div class="invalid-feedback">
                Dette feltet er obligatorisk
            </div>
        </div>
        <div class="col-2">
            <label>Poststed</label>
            <input name="Patient[City]" type="text" class="form-control" required>
            <div class="invalid-feedback">
                Dette feltet er obligatorisk
            </div>
        </div>
    </div>
    <p><a data-bs-toggle="collapse" href="#input-campaign-code">Har du en kampanje- eller vervekode?</a></p>
    <div class="collapse" id="input-campaign-code">
        <label for="CampaignCode">Kampanjekode</label>
        <input class="form-control" type="text" name="CampaignCode" id="CampaignCode">
    </div>
    <label for="FreeTextMessage">Beskjed til klinikken (valgfri)</label>
    <textarea class="form-control" name="FreeTextMessage" id="FreeTextMessage" rows="4"></textarea>
    <label for="privacy-consent">
        <input type="checkbox" name="privacy-consent" id="privacy-consent" required> Jeg samtykker til at personopplysningene jeg sender inn her kan behandles i tråd med <?= get_bloginfo('name') ?> sin personvernerklæring.
        <div class="invalid-feedback">
            Dette må godkjennes for å bestille time på nett
        </div>
    </label>
    <h3>Oppsummering</h3>
    <ul>
        <li>Behandling: <?= $formdata['treatment']['Name'] ?></li>
        <li>Behandler: <?= $formdata['clinician']['Name'] ?></li>
        <li>Tidspunkt: <?= wp_date('D j. F Y \k\l. H:i', date_timestamp_get(date_create($_POST['selectedtime']))); ?></li>
    </ul>
    <button type="submit" class="form-control">Send bestilling <i class="fa fa-check"></i></button>
</form>