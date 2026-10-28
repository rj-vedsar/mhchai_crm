<?php
require_once('./config/autoload.php');

$filename = "item-list.csv";

// Output buffer clean karein (agar autoload.php me koi space/echo ho to error na aaye)
if (ob_get_level()) ob_end_clean();

// Browser Headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Excel UTF-8 Fix
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Column Headers
fputcsv($output, ['Item Name', 'Quantity', 'kg/Qty', 'Remarks']);

// Database se query
$items = $action->db->sql("SELECT item_name FROM aimo_item WHERE deleteStatus = 0");

if ($items) {
    foreach ($items as $ip) {
        fputcsv($output, [
            $ip['item_name'],
            '',
            '',
            ''
        ]);
    }
}

fclose($output);
exit();
?>
