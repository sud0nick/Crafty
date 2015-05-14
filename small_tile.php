<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
require $pineapple->directory . "/functions.php";
global $rel_dir;

if (!dependsInstalled()){
	echo "
	<html>
	<head>
	<style>@import url('" . $rel_dir . "/includes/css/infusion.css')</style>
	<script>
	$('#crafty_install_div').on('click','#crafty_installDepends',function(){
		$('#crafty_install_div').html('<progress></progress>');
		$.post('/components/infusions/crafty/functions.php',{installDepends:''},function(data){
			if (data == true){
				refresh_small('crafty');
			} else {
				alert('Failed to install depends');
				$('#crafty_install_div').html('<button id=\'crafty_installDepends\' class=\'crafty_button\'>Install hping3</button>');
			}
		});
	});
	</script>
	</head>
	<body>
		<div id='crafty_install_div' style='text-align:center'>
			<button id='crafty_installDepends' class='crafty_button'>Install hping3</button>
		</div>
	</body>
	</html>";
	exit();
}
?>
<html>
<head>
<style>@import url('<?php echo $rel_dir; ?>/includes/css/infusion.css')</style>
<script>
$('#crafty_command_select').html("<option>Loading...</option>");
$.post("/components/infusions/crafty/functions.php",{getCommands:""},function(data){
	var options = "";
	if (data != false){
		var pairs = data.split(";");
		for (var x=0; x < pairs.length; x++){
			var single_pair = pairs[x].split(":");
			options += "<option value='" + single_pair[1] + "'>" + single_pair[0] + "</option>";
		}
	} else {
		options = "<option value='none'>No Saved Commands</option>";
	}
	$('#crafty_command_select').html(options);
});
$('#crafty_small_tile_execute').on('click',function(){
	var command = $('#crafty_command_select').val();
	if (command == "none") {
		alert("You have no saved commands!");
		return;
	}
	var target_addr = $('#crafty_small_tile_target').val();
	if (target_addr == ""){
		alert("You must specify a target");
		return;
	}
	$.post("/components/infusions/crafty/functions.php",{small_exec:{target:target_addr, cmd:command}},function(data){
		if (data == true){
			$('#crafty_title').click();
		} else {
			alert("Failed to execute command!");
		}
	});
});
</script>
</head>
<body>
	<div style="text-align: right">
		<a href="#" class="refresh" onclick="refresh_small('crafty');"></a>
	</div>
	<div class='crafty_small_tile_main'>
		<br />
		<input type="text" class="crafty_crafter_field" style="width: 70%" placeholder="Target Address/Hostname" id="crafty_small_tile_target"/>
		<br /><br />
		<select class='crafty_select' id='crafty_command_select'>
			
		</select><br /><br />
		<button class="crafty_button_small" id="crafty_small_tile_execute">Execute</button>
	</div>
</body>
</html>