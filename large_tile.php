<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
require_once $pineapple->directory . "/functions.php";

if (dependsInstalled()) {
	$pineapple->drawTabs(array("Crafter", "Commands", "Change Log"));
} else if (!dependsInstalled()) {
	echo "<h1 style='color: #FF0000; text-align:center'>Dependencies must be installed!</h1>";
}
?>