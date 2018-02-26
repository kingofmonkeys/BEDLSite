<?php
include "common.php";

$output ="";
$output .= draw_head("Baltimore English Dart League", "Welcome to the Baltimore English Dart League Website");


$output .= LoadInfo("./properties/locations.txt");


$output .= draw_foot();

echo $output;

?>
