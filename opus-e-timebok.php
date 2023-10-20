<?php

/**
 * @wordpress-plugin
 * Plugin Name: Online timebestilling
 * Description: Muliggjør timebestilling via API fra Opus Online
 * Version: 1.0.0
 * Author: Fikse Design
 * Author URI: https://www.fikse-design.no
 */
date_default_timezone_set('Europe/Oslo');

function opus_frontend_scripts()
{
    $dir = plugin_dir_url(__FILE__);
    $version = '2.0.8';
    // stiler
    wp_enqueue_style('js-calendar', $dir.'css/jsCalendar.min.css');
    wp_enqueue_style('js-calendar-dark', $dir.'css/jsCalendar.darkseries.min.css');
    wp_enqueue_style('opus-style', $dir.'css/opus-styles.css', [], $version);
    // script
    wp_register_script('loading-overlay', $dir.'/js/loadingoverlay.min.js', ['jquery'], $version, true);
    wp_enqueue_script('opus-ajax', $dir.'js/opus-ajax.js', ['jquery', 'loading-overlay'], $version, true);
    wp_enqueue_script('bootstrap', $dir.'js/bootstrap.min.js', ['jquery'], $version, true);
    wp_enqueue_script('js-calendar', $dir.'js/jsCalendar.min.js', ['opus-ajax'], 0, true);
    wp_enqueue_script('js-calendar-no', $dir.'js/jsCalendar.lang.no.js', ['js-calendar'], 0, true);
    wp_localize_script('opus-ajax', 'ajax', ['url' => admin_url().'admin-ajax.php']);
}

function opus_backend_scripts()
{
    $dir = plugin_dir_url(__FILE__);
    $version = '2.0.5';
    wp_enqueue_script('opus-settings', $dir.'/js/opus-settings.js', ['jquery', 'wp-util', 'jquery-ui-sortable'], $version, true);
}

add_action('admin_enqueue_scripts', 'opus_backend_scripts');
add_action('wp_enqueue_scripts', 'opus_frontend_scripts');

function opus_after_section_1()
{
    if (get_option('opus_api_key') && get_option('opus_api_live_url')) {
        ob_start(); ?>
        <button class="button button-small button-secondary" id="opus-check-connection">Test sammenkoblingen</button>
        <hr><?php
        $html = ob_get_clean();

        return $html;
    }
    return '<hr>';
}

function opusdental_settings_init()
{
    // import setting sections
    include_once 'settings/setting-sections.php';

    // import setting fields
    include_once 'settings/setting-fields.php';
}
add_action('admin_init', 'opusdental_settings_init');

function opusdental_options_page()
{
    add_menu_page(
        'Innstillinger for online timebestilling via Opus Dental',
        'Opus online',
        'manage_options',
        'opus-settings',
        'opusdental_options_page_cb',
        'dashicons-chart-pie',
        5
    );
}
add_action('admin_menu', 'opusdental_options_page');

function opusdental_options_page_cb()
{
    include_once 'templates/admin/options.php';
}
// callbacks
include_once('settings/setting-field-callbacks.php');

function opus_live_mode_active() {
    $live = (get_option('opus_force_offline_mode') == 1) ? false : true;
    return $live;
}

// Generell funksjonalitet
add_filter('wp_ajax_test_opus_connection', 'test_opus_connection');
function test_opus_connection()
{
    $data = opus_get_treatments();
    if ($data) {
        wp_send_json_success($data);
    } else {
        wp_send_json_error($data);
    }
}

function opus_get_treatments()
{   
    if (!opus_live_mode_active()) {
        return new WP_Error('livemode_disabled');
    }
    return send_opus_get_request('treatments');
}

function opus_get_clinicians($TreatmentID = null)
{
    return send_opus_get_request('clinicians', ['treatmentID' => $TreatmentID]);
}

function opus_sort_clinicians($a, $b)
{
    $order = get_option('opus_clinicians_preferred_order');
    if (empty($order)) {
        return;
    }
    $order = array_flip($order);

    return $order[$a->Name] <=> $order[$b->Name];
}

add_filter('wp_ajax_form_get_clinicians', 'form_get_clinicians');
add_filter('wp_ajax_nopriv_form_get_clinicians', 'form_get_clinicians');
function form_get_clinicians()
{
    $return = [];
    parse_str($_POST['inputs'], $formdata);
    $clinicians = opus_get_clinicians($formdata['treatment']['ID']);
    $return['clinicians'] = $clinicians;
    uasort($clinicians, 'opus_sort_clinicians');
    // wp_send_json_success($clinicians);
    if ($clinicians) {
        ob_start();
        include_once plugin_dir_path(__FILE__).'templates/ajax-form-clinicians.php';
        $return['html'] = ob_get_clean();
        wp_send_json_success($return);
    }
    $return['html'] = '<p>Ingen behandlere tilbyr dette til nye pasienter for øyeblikket.</p>';
    wp_send_json_error($return);
}

function opus_get_month_overview($TreatmentID = null, $ClinicianID = null, $year = null, $month = null)
{
    $today = new DateTime();
    $args = [
      'year' => isset($year) ? $year : date_format($today, 'Y'),
      'month' => isset($month) ? $month : date_format($today, 'm'),
      'TreatmentID' => $TreatmentID,
      'ClinicianID' => $ClinicianID,
    ];

    return send_opus_get_request('timeslots', $args);
}

function opus_get_overview_from_date($TreatmentID = null, $ClinicianID = null, $fromDate = null)
{
    if (!isset($fromDate)) {
        $fromDate = new DateTime();
    }
    $args = [
      'from' => date_format($fromDate, 'Y-m-d\TH:i:s'),
      'to' => date_format(date_modify($fromDate, '+60 days'), 'Y-m-d\TH:i:s'),
      'TreatmentID' => $TreatmentID,
      'ClinicianID' => $ClinicianID,
    ];

    return send_opus_get_request('appointments/schedule/overview/availabletimes', $args);
}

add_filter('wp_ajax_form_get_hours', 'form_get_hours');
add_filter('wp_ajax_nopriv_form_get_hours', 'form_get_hours');
function form_get_hours()
{
    $return = [];
    parse_str($_POST['inputs'], $formdata);
    if (isset($_POST['date'])) {
        $postdate = new DateTime($_POST['date']);
        $now = new DateTime();
        if ($postdate < $now) {
            $postdate = $now;
        }
        $hours = opus_get_overview_from_date($_POST['treatment'], $formdata['clinician']['ID'], $postdate);
    // $hours = opus_get_month_overview($_POST['treatment'], $_POST['clinician'], date_format($postdate, 'Y'), date_format($postdate, 'm'));
    } else {
        // $hours = opus_get_month_overview($_POST['treatment'], $_POST['clinician']);
        $hours = opus_get_overview_from_date($_POST['treatment'], $formdata['clinician']['ID']);
    }
    if ($hours) {
        $return['raw'] = $hours;
        $return['now'] = new DateTime();
        foreach ($hours as $hour) {
            $date = new DateTime($hour->Start); // bruk end i stedet og legg til parameter for $duration (tid i minutter) så vi kan sjekke om det kan klemmes inn en avtale
            if ($date < $return['now']) {
                continue;
            }
            // $return['hours'][date_format($date, 'd/m/Y')][] = $hour; //splitt disse opp etter $duration
            $return['hours'][date_format($date, 'd/m/Y')][] = [
                'formatted' => wp_date('\k\l. H:i', date_timestamp_get($date)),
                'returnvalue' => $hour->Start,
            ];
        }
        $return['dates'] = array_keys($return['hours']);
    } else {
        $return['dates'] = false;
    }
    ob_start();
    include_once plugin_dir_path(__FILE__).'templates/ajax-form-hours.php';
    $return['html']['calendar'] = ob_get_clean();
    // ob_start();
    // include_once(plugin_dir_path(__FILE__).'templates/ajax-form-hours-only.php');
    // $return['html']['hours'] = ob_get_clean();
    wp_send_json_success($return);
}

function opus_get_timeslots($startingFromDateTime = null, $treatmentId = null, $ClinicianID = null)
{
    $args = [
      'startingFromDateTime' => $startingFromDateTime,
      'treatmentId' => $treatmentId,
      'ClinicianID' => $ClinicianID,
    ];

    return send_opus_get_request('timeslots', $args);
}

add_filter('wp_ajax_form_get_timeslots', 'form_get_timeslots');
add_filter('wp_ajax_nopriv_form_get_timeslots', 'form_get_timeslots');
function form_get_timeslots()
{
    $date = date('Y-m-d', new Date($_POST['date']));
    $timeslots = opus_get_timeslots(
        date('Y-m-d\TH:i:s', $date),
        $_POST['treatment'],
        $_POST['clinician'],
    );
    $return['raw'] = $timeslots;
    $return['date'] = $date;
    $return['html'] = '';
    foreach ($timeslots as $timeslot) {
        if ($datecheck !== date('Y-m-d', strtotime($timeslot->Start))) {
            continue;
        }
        $return['html'] .= '<button>Kl '.date('H:i', strtotime($timeslot->Start)).' - '.date('H:i', strtotime($timeslot->End)).'</button>';
    }
    wp_send_json_success($return);
}

function send_opus_get_request($request = null, $query_args = [])
{
    $url = trailingslashit(get_option('opus_api_live_url')).'api/public/v1/'.$request;
    if (!empty($query_args)) {
        $url = add_query_arg($query_args, $url);
    }
    $key = get_option('opus_api_key');
    $http = wp_remote_get($url, [
      'headers' => ['api_key' => $key],
      'timeout' => 20,
    ]);
    if (200 == wp_remote_retrieve_response_code($http)) {
        return json_decode(wp_remote_retrieve_body($http));
    }
    if ($request == 'treatments') {
        return $http;
    }
    if (current_user_can('update_core')) {
        return $http;
    }

    return false;
}

function send_opus_post_request($request = null, $query_args = [], $body = [])
{
    $url = trailingslashit(get_option('opus_api_live_url')).'api/public/v1/'.$request;
    if (!empty($query_args)) {
        $url = add_query_arg($query_args, $url);
    }
    $key = get_option('opus_api_key');
    $http = wp_remote_post($url, [
        'headers' => [
            'api_key' => $key,
            'Content-Type' => 'application/json',
        ],
        'timeout' => 20,
        'body' => wp_json_encode($body),
    ]);

    return $http;
}

function opus_split_duration($start, $end, $duration) // trenger arbeid - men er kanskje ikke nødvendig? Vi avventer!
{
    $return = [];
}

add_filter('wp_ajax_get_booking_form', 'get_booking_form');
add_filter('wp_ajax_nopriv_get_booking_form', 'get_booking_form');
function get_booking_form()
{
    parse_str($_POST['inputs'], $formdata);
    ob_start();
    include_once plugin_dir_path(__FILE__).'templates/ajax-form-booking.php';
    $return = [];
    $return['html'] = ob_get_clean();
    $return['raw'] = $_POST;
    wp_send_json_success($return);
}

add_filter('wp_ajax_do_opus_booking', 'do_opus_booking');
add_filter('wp_ajax_nopriv_do_opus_booking', 'do_opus_booking');
function do_opus_booking()
{
    parse_str($_POST['formdata'], $formdata);
    unset($formdata['privacy-consent']);
    $formdata['Patient']['MobilePhoneNumber'] = '+47'.$formdata['Patient']['MobilePhoneNumber'];
    $query_args = [
        'notifyPatient' => true,
    ];
    $booking = send_opus_post_request('bookings', $query_args, $formdata);
    $status = wp_remote_retrieve_response_code($booking);
    if (200 == $status) {
        ob_start();
        include_once plugin_dir_path(__FILE__).'templates/ajax-form-finish.php';
        $html = ob_get_clean();
        $return = [
            'html' => $html,
        ];
        $signature = get_option('opus_api_signature');
        wp_mail($formdata['Patient']['Email'], 'Din timebestilling hos '.get_bloginfo('name').'', $html.$signature, ['Content-type: text/html']);
        wp_send_json_success($return);
    }
    wp_send_json_error($booking); // sett inn fallback til "gammeldags" skjema her
}


add_filter('wp_ajax_opus_get_fallback_clinicians', 'opus_get_fallback_clinicians');
add_filter('wp_ajax_nopriv_opus_get_fallback_clinicians', 'opus_get_fallback_clinicians');
function opus_get_fallback_clinicians() {
    $clinicians = get_option('opus_clinicians_preferred_order');
    ob_start();
    include_once('templates/fallback-form-clinicians.php');
    $return = ob_get_clean();
    wp_send_json_success($return);
}

add_filter('wp_ajax_opus_get_fallback_daytimes', 'opus_get_fallback_daytimes');
add_filter('wp_ajax_nopriv_opus_get_fallback_daytimes', 'opus_get_fallback_daytimes');
function opus_get_fallback_daytimes() {
    $return = [];
    ob_start();
    include_once('templates/fallback-form-days.php');
    $return['days'] = ob_get_clean();
    ob_start();
    include_once('templates/fallback-form-times.php');
    $return['times'] = ob_get_clean();
    wp_send_json_success($return);
}

add_filter('wp_ajax_opus_get_fallback_form', 'opus_get_fallback_form');
add_filter('wp_ajax_nopriv_opus_get_fallback_form', 'opus_get_fallback_form');
function opus_get_fallback_form() {
    parse_str($_POST['daytime'], $daytime);
    $return = [];
    ob_start();
    include_once('templates/fallback-form-patient.php');
    $return['html'] = ob_get_clean();
    wp_send_json_success($return);
}

add_filter('wp_ajax_opus_submit_fallback_form', 'opus_submit_fallback_form');
add_filter('wp_ajax_nopriv_opus_submit_fallback_form', 'opus_submit_fallback_form');
function opus_submit_fallback_form() {
    $errors = new WP_Error();
    parse_str($_POST['formdata'], $formdata);
    $receiver = empty(get_option('opus_fallback_email_notification')) ? get_bloginfo('admin_email') : get_option('opus_fallback_email_notification');
    ob_start();
    include_once('templates/admin/booking-email.php');
    $content = ob_get_clean();
    add_filter('wp_mail_from', function($email){
        return str_replace( 'wordpress@', 'bestilling@', $email );
    });
    add_filter('wp_mail_from_name', function(){
        return get_bloginfo('name').' - Timebestilling';
    });
    $mail = wp_mail(
        $receiver,
        'Ny timebestilling via '.site_url(),
        $content,
        [
            'Content-type: text/html'
        ]
    );
    if ($mail) {
        ob_start();
        include_once('templates/fallback-form-finish.php');
        $html = ob_get_clean();
        wp_send_json_success(['html' => $html]);
    }
    $errors->add('failed_to_send_email', 'Bestillingen ble ikke registrert');
    wp_send_json_error($errors);
}

add_shortcode(
    'opus-timebestilling',
    function () {
        if (!is_user_logged_in()) {
            // return '<p>Midlertidig deaktivert</p>'; sette inn funksjon for testmodus?
        }
        $treatments = opus_get_treatments();
        ob_start();
        if (is_wp_error($treatments)) {
            include_once plugin_dir_path(__FILE__).'templates/fallback-form-start.php';
        } else {
            include_once plugin_dir_path(__FILE__).'templates/ajax-form-start.php';
        }
        $return = ob_get_clean();

        return $return;
    }
);
?>