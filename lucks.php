<?php
include "common.php";

$output = "";
$output .= draw_head("Baltimore English Dart League", "Upcoming Events");


$output .= LoadEvents("./properties/lucks.txt");

$output .= draw_foot();

echo $output;

?>
