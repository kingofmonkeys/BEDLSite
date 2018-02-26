<?php
include "common.php";
$output = "";
$output .= draw_head("Baltimore English Dart League", "Baltimore English Dart League Photo Gallery");


$header = 'Photo Gallery';


$body .= 'Photo Gallery Coming Soon...';

$output .= drawContainer($header,$body);


$output .= draw_foot();

echo $output;

?>