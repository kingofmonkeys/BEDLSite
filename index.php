<?php include "common.php";

$output = "";

$output .= draw_head("Baltimore English Dart League", "Welcome to the Baltimore English Dart League Website");

$output .= "<div class='grids-row'>";
$output .= "<div class='grids-column grids-column-4-5'>";
$output .= Loadinfo("./properties/info.txt");

$output .= Loadnews("./properties/news.txt");
$output .= "</div>";
$output .= "<div class='grids-column grids-column-1-5'>";

$output .= LoadSidebar("./properties/joinside.txt");
$output .= LoadSidebar("./properties/links.txt");
$output .= "</div>";
$output .= "</div>";

$output .= draw_foot();

echo $output;

?>
