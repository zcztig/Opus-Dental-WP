<?php
function opus_api_settings_cb()
{
    echo '<p>Fyll ut informasjon som du har fått fra Opus Dental her.</p>';
    echo '<p>Ta i bruk løsningen ved sette inn kortkoden <code>[opus-timebestilling]</code> på en side eller et inlegg.</p>';
}

function opus_fallback_settings_cb()
{
    echo '<p>Fyll ut feltene her for å muliggjøre bestillinger hvis vi ikke får kontakt med Opus sine servere</p>';
}

function opus_api_key_cb($args)
{ ?>
<input type="text" class="regular-text" name="opus_api_key" id="opus_api_key"
	value="<?php echo get_option('opus_api_key'); ?>"
	placeholder="<?php echo $args['placeholder']; ?>"><?php
}

function opus_api_live_url_cb($args)
{ ?>
    <input type="url" class="regular-text" name="opus_api_live_url" id="opus_api_live_url"
	value="<?php echo get_option('opus_api_live_url'); ?>"
	placeholder="<?php echo $args['placeholder']; ?>"><?php
}

function opus_api_signature_cb() {
    $signature = get_option('opus_api_signature');
    wp_editor($signature, 'opus_api_signature', );
}

function opus_clinicians_preferred_order_cb($args)
{
    $order = get_option('opus_clinicians_preferred_order'); ?>
    <div id="clinician-order" class="opus-sortable">
        <?php if ($order) { ?>
        <?php foreach ($order as $clinician) { ?>
        <div>
            <input type="text" class="regular-text" name="opus_clinicians_preferred_order[]"
                value="<?php echo $clinician; ?>">
            <button type="button" class="button button-secondary clinician-remove">Fjern</button>
            <?php if (count($order) > 1) { ?>
            <span class="handle"><i class="fa fa-arrows"></i></span>
            <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
    <button type="button" class="button button-secondary" id="clinician-add">Legg til</button>
    <hr>
    <p><small><?php echo $args['helptext']; ?></small>
    </p>
    <script id="tmpl-clinician-order" type="text/html">
        <div>
            <input type="text" class="regular-text" name="opus_clinicians_preferred_order[]" value="">
            <button type="button" class="button button-secondary clinician-remove">Fjern</button>
        </div>
    </script><?php
}

function opus_fallback_treatments_cb($args)
{
    $treatments = get_option('opus_fallback_treatments'); ?>
    <div id="opus-fallback-treatments" class="opus-sortable">
        <?php if ($treatments) { ?>
        <?php foreach ($treatments as $treatment) { ?>
        <div>
            <input type="text" name="opus_fallback_treatments[]" class="regular-text"
                value="<?php echo $treatment; ?>">
            <button type="button" class="button button-secondary treatment-remove">Fjern</button>
            <?php if (count($treatments) > 1) { ?>
            <span class="handle"><i class="fa fa-arrows"></i></span>
            <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
    <button type="button" class="button button-secondary" id="treatment-add">Legg til</button>
    <script id="tmpl-fallback-treatments" type="text/html">
        <div>
            <input type="text" class="regular-text" name="opus_fallback_treatments[]" value="">
            <button type="button" class="button button-secondary treatment-remove">Fjern</button>
        </div>
    </script><?php
}

function opus_force_offline_mode_cb($args)
{
    printf(
        '<input type="checkbox" name="opus_force_offline_mode" value="1" %1$s>',
        (1 == get_option('opus_force_offline_mode')) ? 'checked' : ''
    );
    echo '<p><small>'.$args['helptext'].'</small></p>';
}

function opus_fallback_email_notification_cb($args)
{
    printf(
        '<input type="email" name="opus_fallback_email_notification" value="%1$s">',
        get_option('opus_fallback_email_notification')
    );
    echo '<p><small>'.$args['helptext'].'</small></p>';
}
?>