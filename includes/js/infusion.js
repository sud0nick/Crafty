var getOutputInterval;
var currentTab = false;
$('#crafty_target_tab').css('border-color', 'white');
$('#crafty_source_section,#crafty_options_section,#crafty_packets_section,#crafty_save_section').css('display', 'none');

$('#crafty_target_port,#crafty_source_address,#crafty_source_port,#crafty_packetCount,#crafty_sendInterval,#crafty_ttl,#crafty_window_size,#crafty_tcp_data_offset,#crafty_add_signature,#crafty_define_sequence,#crafty_fragment_offset').prop('disabled', true);

$.post("/components/infusions/crafty/functions.php",{getWiFiInterfaces:""},function(data){
	var options = "<option value='auto'>Auto</option>";
	if (data != false) {
		var opts = data.split("\n");
		for (var x=0; x < opts.length; x++) {
			options += "<option value='" + opts[x] + "'>" + opts[x] + "</option>";
		}
	}
	$('#crafty_interface_select').html(options);
});

// Check if there is a current process running
$.post("/components/infusions/crafty/functions.php",{getPID:""},function(data){
	if (data != false) {
		$('#crafty_current_pid').html(data+" : <button onclick='stopCommand()'>Kill</button>");
		$('#crafty_current_status').html("Executing");
		
		$.post("/components/infusions/crafty/functions.php",{getCmd:""},function(data){
			if (data != "") {
				$('#crafty_current_command').html(data);
			}
		});
		
		// Clear the interval if it already exists
		if (getOutputInterval) {
			clearInterval(getOutputInterval);
		}
		// Create an interval
		getOutputInterval = setInterval(function(){refreshOutput()}, 1000);
	}
});

function buildCommand(){
	var params = new Array();
	
	// Get the selected mode
	if ($('#crafty_mode_select').val() != "none") {
		params.push($('#crafty_mode_select').val());
	}

	// Get the target address
	if ($('#crafty_target_address').val() == ""){
		alert("You must define a target!");
		return false;
	}
	params.push($('#crafty_target_address').val());

	// Check if all selected fields have been filled out
	if (!checkSelectedFields()) {
		return false;
	}
	
	// Check for spoofed source address
	if ($('#crafty_spoof_source_check').is(':checked')){
		params.push("-a " + $('#crafty_source_address').val());
	}

	// Get the selected interface
	if ($('#crafty_interface_select').val() != "auto"){
		params.push("-I " + $('#crafty_interface_select').val());
	}

	// Check if the packet count is defined
	if ($('#crafty_packetCount_check').is(':checked')){
		params.push("-c " + $('#crafty_packetCount').val());
	}

	// Check if the send interval is defined
	if ($('#crafty_sendInterval_check').is(':checked')){
		if ($('#crafty_sendInterval_select').val() != "Custom"){
			params.push($('#crafty_sendInterval_select').val());
		} else {
			params.push("-i " + $('#crafty_sendInterval').val());
		}
	}
	
	// Check if TTL is defined
	if ($('#crafty_ttl_check').is(':checked')){
		params.push("-t " + $('#crafty_ttl').val());
	}

	// Check if traceroute is selected
	if ($('#crafty_traceroute').is(':checked')){
		params.push("-T");
	}

	// Check if the source port is defined
	if ($('#crafty_define_src_port_check').is(':checked')){
		params.push("-s " + $('#crafty_source_port').val());
	}

	// Check if 'no increment' is :checked
	if ($('#crafty_no_increment').is(':checked')){
		params.push("-k");
	}

	// Check if a target port is defined
	if ($('#crafty_define_target_port_check').is(':checked')){
		params.push("-p " + $('#crafty_target_port').val());
	}

	// Check if the window size is defined
	if ($('#crafty_define_window_size_check').is(':checked')){
		params.push("-w " + $('#crafty_window_size').val());
	}
	
	// Check if the TCP data offset is defined
	if ($('#crafty_tcp_data_offset_check').is(':checked')){
		params.push("-O " + $('#crafty_tcp_data_offset').val());
	}

	// Check if the TCP sequence number is defined
	if ($('#crafty_define_sequence_check').is(':checked')){
		params.push("-M " + $('#crafty_define_sequence').val());
	}
	
	// Check if the TCP sequence number should be displayed alone
	if ($('#crafty_tcp_sequence_check').is(':checked')){
		params.push("-Q ");
	}
	
	// Check for packet dump options
	if ($('#crafty_dump_hex_check').is(':checked')){
		params.push("-j");
	}
	if ($('#crafty_dump_printable_check').is(':checked')){
		params.push("-J");
	}
	
	// Check if a signature should be added
	if ($('#crafty_add_signature_check').is(':checked')){
		params.push("-e "+$('#crafty_add_signature').val());
	}

	// Check which packet flags are set
	if ($('#crafty_flag_tcp_ack').is(':checked')){params.push("-L");}
	if ($('#crafty_flag_ack').is(':checked')){params.push("-A");}
	if ($('#crafty_flag_fin').is(':checked')){params.push("-F");}
	if ($('#crafty_flag_urg').is(':checked')){params.push("-U");}
	if ($('#crafty_flag_syn').is(':checked')){params.push("-S");}
	if ($('#crafty_flag_rst').is(':checked')){params.push("-R");}
	if ($('#crafty_flag_xmas').is(':checked')){params.push("-X");}
	if ($('#crafty_flag_ymas').is(':checked')){params.push("-Y");}
	if ($('#crafty_flag_push').is(':checked')){params.push("-P");}

	// Check if fragmentation is defined
	var frag;
	if ($('#crafty_define_fragment_check').is(':checked')){
		if ($('#crafty_fragment_amount').val() == "Custom"){
			params.push("-g " + $('#crafty_fragment_offset').val());
		} else {
			params.push($('#crafty_fragment_amount').val());
		}
	}
	
	// Build the command
	var command = "hping3 ";
	for (var x=0; x < params.length; x++){
		command += params[x] + " ";
	}
	return command;
}
$('#crafty_target_tab, #crafty_source_tab, #crafty_options_tab, #crafty_packets_tab').on("click",function(){
	if (currentTab == this) {
		return;
	} else {
		currentTab = this;
	}
	$('#crafty_target_tab, #crafty_source_tab, #crafty_options_tab, #crafty_packets_tab').css('border-color', '#34D134');
	$(this).css('border-color','white');
	
	var tabName = $(this).html();
	$('#crafty_target_section, #crafty_source_section, #crafty_options_section, #crafty_packets_section').css('display', 'none');
	if (tabName == "Target"){
		$('#crafty_target_section').fadeIn('slow');
	} else if (tabName == "Source"){
		$('#crafty_source_section').fadeIn('slow');
	} else if (tabName == "Options"){
		$('#crafty_options_section').fadeIn('slow');
	} else if (tabName == "Packets"){
		$('#crafty_packets_section').fadeIn('slow');
	}
});
$('#crafty_build_button').on("click",function(){
	var command = buildCommand();
	if (!command){return}
	$('#crafty_current_command').html(command);
});
$('#crafty_exec_button').on("click",function(){
	var command = buildCommand();
	if (!command){return}
	$.post("/components/infusions/crafty/functions.php",{startExec:command},function(data){
		if (data != false) {
			// Clear the shell
			$('#crafty_shell').val("");
			
			// Clear the interval if it already exists
			if (getOutputInterval) {
				clearInterval(getOutputInterval);
			}
			
			// Create an interval
			getOutputInterval = setInterval(function(){refreshOutput()}, 1000);
			
			// Update the status
			$('#crafty_current_status').html("Executing");

			// Display the command
			$('#crafty_current_command').html(command);
			
			// Display the PID for the user
			$('#crafty_current_pid').html(data+" : <button onclick='stopCommand()'>Kill</button>");
			
			// Disable the exec button
			$('#crafty_exec_button').prop('disabled',true);
		} else {
			alert("Command failed");
		}
	});
});
$('#crafty_save_command_button').on("click",function(){
	// Build and validate the command
	var command = buildCommand();
	if (!command){return}
	$('#crafty_save_command').html(command);
	
	// Fade out the options divs and set the buttons' background to black
	$('#crafty_target_section,#crafty_source_section, #crafty_options_section, #crafty_packets_section').css('display', 'none');
	$('#crafty_target_tab, #crafty_source_tab, #crafty_options_tab, #crafty_packets_tab').css('border-color', '#34D134');
	$('#crafty_target_tab, #crafty_source_tab, #crafty_options_tab, #crafty_packets_tab,#crafty_build_button,#crafty_exec_button,#crafty_save_command_button,#crafty_clear_options_button').prop('disabled', true);
	
	// Fade in the save command div and make the button's background white
	$('#crafty_save_section').fadeIn("slow");
});
$('#crafty_confirm_save').on("click",function(){
	// Verify a title exists
	var title = $('#crafty_command_title').val().replace(':','').replace(';','');
	if (title == ""){
		alert("Please enter a descriptive title for this command");
		return;
	}
	
	// Rebuild the command
	var command = buildCommand();
	if (!command){return}
	
	// Send the command to functions.php
	$.post("/components/infusions/crafty/functions.php",{saveCommand:{"title": title, "command": command}},function(data){
		if (data == true){
			refresh_small("crafty");
			cleanAndCloseSavePanel();
		}
	});
});
$('#crafty_cancel_save').on("click",function(){
	// Clear the title text box and close the save panel
	cleanAndCloseSavePanel();
});
function cleanAndCloseSavePanel(){
	$('#crafty_save_command').html("");
	$('#crafty_command_title').val("");
	$('#crafty_target_tab, #crafty_source_tab, #crafty_options_tab, #crafty_packets_tab,#crafty_build_button,#crafty_exec_button,#crafty_save_command_button,#crafty_clear_options_button').prop('disabled', false);
	$('#crafty_target_tab').css('border-color', 'white');
	$('#crafty_save_section').css('display', 'none');
	$('#crafty_target_section').fadeIn("slow");
}
$('#crafty_clear_options_button').on("click",function(){
	// Reset all select elements
	$('#crafty_mode_select,#crafty_interface_select,#crafty_sendInterval_select,#crafty_fragment_amount').prop('selectedIndex',0);
	
	// Reset all text fields
	$('#crafty_target_address,#crafty_source_address,#crafty_packetCount,#crafty_sendInterval,#crafty_ttl,#crafty_source_port,#crafty_target_port,#crafty_window_size,#crafty_tcp_data_offset,#crafty_add_signature,#crafty_define_sequence,#crafty_fragment_offset').val("");
	
	// Reset all checkboxes
	$('#crafty_spoof_source_check,#crafty_packetCount_check,#crafty_sendInterval_check,#crafty_ttl_check,#crafty_traceroute,#crafty_define_src_port_check,#crafty_no_increment,#crafty_define_target_port_check,#crafty_define_window_size_check,#crafty_tcp_data_offset_check,#crafty_define_sequence_check,#crafty_tcp_sequence_check,#crafty_dump_hex_check,#crafty_dump_printable_check,#crafty_add_signature_check,#crafty_flag_tcp_ack,#crafty_flag_ack,#crafty_flag_fin,#crafty_flag_urg,#crafty_flag_syn,#crafty_flag_rst,#crafty_flag_xmas,#crafty_flag_ymas,#crafty_flag_push,#crafty_define_fragment_check').prop('checked',false);
});
$('#crafty_define_target_port_check,#crafty_spoof_source_check,#crafty_define_src_port_check,#crafty_packetCount_check,#crafty_sendInterval_check,#crafty_ttl_check,#crafty_define_window_size_check,#crafty_tcp_data_offset_check,#crafty_add_signature_check,#crafty_define_sequence_check,#crafty_define_fragment_check').on("click",function(){
	var field = $(this).attr('afield');
	if ($(this).is(':checked')){
		$("#"+field).prop('disabled', false);
	} else {
		$("#"+field).val("");
		$("#"+field).prop('disabled', true);
	}
});
function checkSelectedFields(){
	var checkboxes = ['#crafty_define_target_port_check', '#crafty_spoof_source_check', '#crafty_define_src_port_check', '#crafty_packetCount_check', '#crafty_sendInterval_check', '#crafty_ttl_check', '#crafty_define_window_size_check', '#crafty_tcp_data_offset_check', '#crafty_add_signature_check', '#crafty_define_sequence_check', '#crafty_define_fragment_check'];
	
	var emptyFields = new Array();
	for (var x=0; x < checkboxes.length; x++) {
		if ($(checkboxes[x]).is(':checked')) {
			
			// Check if this is the send interval checkbox
			if (checkboxes[x] == "#crafty_sendInterval_check") {
				if ($('#crafty_sendInterval_select').val() != "Custom"){
					continue;
				}
			}
			
			// Check if this is the define fragmentation checkbox
			if (checkboxes[x] == "#crafty_define_fragment_check") {
				if ($('#crafty_fragment_amount').val() != "Custom"){
					continue;
				}
			}
			
			var field = $(checkboxes[x]).attr('afield');
			if ($("#"+field).val() == "") {
				emptyFields.push(field);
			}
		}
	}
	if (emptyFields.length > 0) {
		alert("You have selected the following fields but left them blank:\n\n" + emptyFields.join("\n"));
		return false;
	}
	return true;
}
function refreshOutput() {
	$.post("/components/infusions/crafty/functions.php",{getOutput:""},function(data){
		var output = data;
		if (output == ""){
			$('#crafty_current_status').html("Executing");
		} else {
			// Verify the process is no longer running
			$.post("/components/infusions/crafty/functions.php",{getPID:""},function(data){
				if (data == false) {
					$('#crafty_current_status').html("Not Running");
					$('#crafty_shell').val(output);
					clearInterval(getOutputInterval);
					$('#crafty_current_pid').html("None");
					$('#crafty_exec_button').prop('disabled',false);
				}
			});
		}
	});
}
function stopCommand(){
	$.post("/components/infusions/crafty/functions.php",{stopCommand:""},function(data){
		if (data == true){
			if (getOutputInterval) {
				clearInterval(getOutputInterval);
			}
			$('#crafty_current_pid').html("None");
			$('#crafty_current_status').html("Not Running");
			$('#crafty_exec_button').prop('disabled',false);
			
			$.post("/components/infusions/crafty/functions.php",{getOutput:""},function(data){
				if (data != ""){
					$('#crafty_shell').val(data);
				}
			});
		} else {
			alert("Failed to kill process");
		}
	});
}