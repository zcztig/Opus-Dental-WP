<?php 
$after_section_1 = opus_after_section_1();
add_settings_section(
    'opus_api_settings',
    'Innstillinger for API',
    'opus_api_settings_cb',
    'opus-settings',
    [
        'after_section' => $after_section_1,
    ]
);

add_settings_section(
    'opus_fallback_settings',
    'Innstillinger for backupløsning',
    'opus_fallback_settings_cb',
    'opus-settings'
);