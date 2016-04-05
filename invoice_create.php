<?php
/**
 * Auto generate an invoice
 * @author Tomas Hujer
 */

require_once 'vendor/fakturoid/Fakturoid.php';
require_once 'config/app.php';
$f = new Fakturoid($a_login['subdomain'], $a_login['login_name'], $a_login['api_hash'], $a_login['recipient']);

/**
 * @param $date
 * @return bool
 */
function isWeekend($date) {
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}

$dt_month = (int) date("m", strtotime('-1 month'));
$dt_year = (int) date("Y", strtotime('-1 month'));
$nl_day_count = cal_days_in_month(CAL_GREGORIAN, $dt_month, $dt_year);

for($dt_day = 1; $dt_day <= $nl_day_count; $dt_day++) {
    if(!isWeekend("$dt_day.$dt_month.$dt_year")) {
        $lines[] = array('name' => "$dt_day.$dt_month.$dt_year Intranet work", 'quantity' => 8, 'unit_name' => 'hod', 'unit_price' => 1);
    }
}

// Get company details
$subject = $f->get_subject($a_app['nl_id_contact']);

// Create invoice
$invoice = $f->create_invoice(array('subject_id' => $subject->id, 'lines' => $lines));

// Send created invoice
//$f->fire_invoice($invoice->id, 'deliver');

// To mark invoice as paid
//$f->fire_invoice($invoice->id, 'pay'); // or 'pay_proforma' for paying proforma and 'pay_partial_proforma' for partial proforma

