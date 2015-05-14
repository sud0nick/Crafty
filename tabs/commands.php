<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir, $configs;
?>
<!DOCTYPE html>
<html>
<head>
<style>
.crafty_cmd_item {
	border: 1px dashed white;
	text-align: left;
	padding: 10px 10px;
}
.crafty_cmd_item_button {
	margin-top: -12px;
	float: right;
}
</style>
<script>
$('#crafty_cmd_mgr_table').html("<h1>Loading...</h1>");
$.post("/components/infusions/crafty/functions.php",{getCommands:""},function(data){
	var options = "";
	if (data != false){
		var pairs = data.split(";");
		for (var x=0; x < pairs.length; x++){
			var single_pair = pairs[x].split(":");
			options += "<div class='crafty_cmd_item' id='"+single_pair[0]+"'>"+single_pair[0] + "<br />" + single_pair[1] + "<button class='crafty_cmd_item_button' id='"+single_pair[0]+"_delete'>x</button></div><br />";
		}
	} else {
		options = "<h1>No Saved Commands</h1>";
	}
	$('#crafty_cmd_mgr_table').html(options);
});
$('#crafty_cmd_mgr_table').on("click", ".crafty_cmd_item_button",function(){
	var parent = $(this).parent();
	if (confirm("Do you want to delete " + $(this).parent().attr('id') + "?")){
		$.post("/components/infusions/crafty/functions.php",{deleteCommand:$(this).parent().attr('id')},function(data){
			if (data == true) {
				parent.remove();
				refresh_small("crafty");
			} else {
				alert("Failed to delete command!");
			}
		});
	}
});
</script>
</head>
<body>
<div class='crafty_crafter_div' id="crafty_cmd_mgr_table">
	
</div>
</body>
</html>
