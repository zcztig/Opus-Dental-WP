<?php 
//Api nøkkel
add_settings_field(
    'opus_api_key',
    'API-nøkkel',
    'opus_api_key_cb',
    'opus-settings',
    'opus_api_settings',
    [
      'placeholder' => 'Skriv API-nøkkel her'
    ]
);
register_setting('opus-settings', 'opus_api_key');

//Url
add_settings_field(
    'opus_api_live_url',
    'URL for livemodus',
    'opus_api_live_url_cb',
    'opus-settings',
    'opus_api_settings',
    [
      'placeholder' => 'Skriv url for liveforespørsler'
    ]
);
register_setting('opus-settings', 'opus_api_live_url');

//Mailsignatur
add_settings_field(
  'opus_api_signature',
  'Mailsignatur for kundebekreftelse',
  'opus_api_signature_cb',
  'opus-settings',
  'opus_api_settings',
  [
    'placeholder' => 'Skriv url for liveforespørsler'
  ]
);
register_setting('opus-settings', 'opus_api_signature');

//Behandlinger (reserve)
add_settings_field(
    'opus_fallback_treatments',
    'Behandlinger',
    'opus_fallback_treatments_cb',
    'opus-settings',
    'opus_fallback_settings',
    [
      'placeholder' => 'Navn på behandling'
    ]
);
register_setting('opus-settings', 'opus_fallback_treatments');

//Behandlere (bestemmer også rekkefølge på live-versjonen)
add_settings_field(
    'opus_clinicians_preferred_order',
    'Behandlere',
    'opus_clinicians_preferred_order_cb',
    'opus-settings',
    'opus_fallback_settings',
    [
      'placeholder' => 'Behandlers fulle navn',
      'helptext' => 'Rekkefølgen her, avgjør også rekkefølgen på live-integrasjonen forutsatt at navnet staves på akkurat samme måte som hos Opus'
    ]
);
register_setting('opus-settings', 'opus_clinicians_preferred_order');

//modus
add_settings_field(
  'opus_force_offline_mode',
  'Tving offline modus',
  'opus_force_offline_mode_cb',
  'opus-settings',
  'opus_fallback_settings',
  [
    'helptext' => 'Aktiver offline-modus for å teste fallback-løsningen'
  ]
);
register_setting('opus-settings', 'opus_force_offline_mode');

add_settings_field(
  'opus_fallback_email_notification',
  'E-post leveres til',
  'opus_fallback_email_notification_cb',
  'opus-settings',
  'opus_fallback_settings',
  [
    'helptext' => 'Timebestillinger sendes til denne adressen. Hvis dette feltet er tomt, leveres det til systemadmin: '.get_bloginfo('admin_email'),
  ]
);
register_setting('opus-settings', 'opus_fallback_email_notification');