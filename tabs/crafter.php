<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir, $configs;
?>
<!DOCTYPE html>
<html>
<head>
<script type='text/javascript' src='/components/infusions/crafty/includes/js/infusion.js'></script>
</head>
</head>
<body>
<div class='crafty_crafter_div'>
	<div class="crafty_options_div">

	<button class="crafty_button_small" id="crafty_target_tab">Target</button>
	<button class="crafty_button_small" id="crafty_source_tab">Source</button>
	<button class="crafty_button_small" id="crafty_options_tab">Options</button>
	<button class="crafty_button_small" id="crafty_packets_tab">Packets</button>
	
	<div class='crafty_crafter_section' id="crafty_target_section">
	<table class='crafty_crafter_table'>
            <tr>
              <td>Target Address</td>
              <td><input class='crafty_crafter_field' type='text' id='crafty_target_address' placeholder='Target Addr / Hostname'/></td>
            </tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
			<td><label>
				<input type="checkbox" id="crafty_define_target_port_check" afield="crafty_target_port">
				Define Target Port</label></td>
				<td><input class="crafty_crafter_field" type="text" id="crafty_target_port" placeholder="Target Port"></td>
			</tr>
	</table>
	</div>
	<div class='crafty_crafter_section' id="crafty_source_section">
	<table class='crafty_crafter_table'>
        <tr>
		<td>Interface</td>
        <td><select id='crafty_interface_select' class='crafty_select'></select></td>
        </tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
            <td><label>
            <input type="checkbox" id="crafty_spoof_source_check" afield="crafty_source_address">
            Spoof Source Address</label></td>
			<td><input class='crafty_crafter_field' type='text' id='crafty_source_address' placeholder='Source Address'/></td>
        </tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
	    <td><label>
			<input type="checkbox" id="crafty_define_src_port_check" afield="crafty_source_port">
			Define Source Port</label></td>
			<td><input class="crafty_crafter_field" type="text" id="crafty_source_port" placeholder="Source Port"></td>
        </tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
	    <td colspan="2"><label>
			<input type="checkbox" id="crafty_no_increment">
			Don't increment source port on send</label>
		</td>
        </tr>
	</table>
	</div>
	<div class='crafty_crafter_section' id="crafty_options_section">
	<table class="crafty_crafter_table">
	    <tr>
	      <td colspan="2"><h3 class="crafty_h3">Mode</h3></td>
	    </tr>
	    <tr>
	      <td><select class="crafty_select" id="crafty_mode_select">
			<option value='none'>None</option>
			<option value="-1">ICMP</option>
	        <option value="-0">Raw IP</option>
	        <option value="-2">UDP</option>
	        <option value="-9">Listen</option>
          </select>
	  </td><td>
		<label><input type="checkbox" id="crafty_traceroute">Traceroute</label>
	  </td>
        </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
        </tr>
		<tr>
	      <td><label>
	        <input type="checkbox" id="crafty_packetCount_check" afield="crafty_packetCount">
			Define Packet Count</label></td>
			<td><input class="crafty_crafter_field" type="text" id="crafty_packetCount" placeholder="Count"></td>
        </tr>
		<tr>
			<td><label><input type="checkbox" id="crafty_sendInterval_check" afield="crafty_sendInterval">
			Define Send Interval</label></td>
			<td><input class="crafty_crafter_field" type="text" id="crafty_sendInterval" placeholder="Interval"></td>
        </tr>
		<tr>
		<td>&nbsp;</td>
	      <td><select class="crafty_select" id="crafty_sendInterval_select">
	        <option value="Custom">Custom</option>
	        <option value="--fast">Fast</option>
	        <option value="--faster">Faster</option>
	        <option value="--flood">Flood</option>
          </select></td>
        </tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
	      <td><label>
			<input type="checkbox" id="crafty_ttl_check" afield="crafty_ttl">
			Define Time to Live</label></td>
			<td><input class="crafty_crafter_field" type="text" id="crafty_ttl" placeholder="TTL"></td>
        </tr>
	    <tr>
	      <td><label>
	        <input type="checkbox" id="crafty_define_window_size_check" afield="crafty_window_size">
          Define Window Size</label></td>
	      <td><input class="crafty_crafter_field" type="text" id="crafty_window_size" placeholder="Window Size"></td>
        </tr>
	    <tr>
	      <td><label>
	        <input type="checkbox" id="crafty_tcp_data_offset_check" afield="crafty_tcp_data_offset">
          Define TCP Data Offset</label></td>
	      <td><input class="crafty_crafter_field" type="text" id="crafty_tcp_data_offset" placeholder="Offset"></td>
        </tr>
		<tr>
	      <td><label>
	        <input type="checkbox" id="crafty_add_signature_check" afield="crafty_add_signature">
          Add Signature</label></td>
	      <td><input class="crafty_crafter_field" type="text" id="crafty_add_signature" placeholder="Signature"></td>
        </tr>
		<tr>
	      <td><label>
	        <input type="checkbox" id="crafty_define_sequence_check" afield="crafty_define_sequence">
          Define TCP Sequence Number</label></td>
	      <td><input class="crafty_crafter_field" type="text" id="crafty_define_sequence" placeholder="Sequence #"></td>
        </tr>
	    <tr>
	      <td colspan="2"><label>
	        <input type="checkbox" id="crafty_tcp_sequence_check">Show TCP Sequence Number Only</label></td>
        </tr>
		<tr>
			<td colspan="2"><label><input type="checkbox" id="crafty_dump_hex_check">Dump packets in hex</label></td>
		</tr>
		<tr>
			<td colspan="2"><label><input type="checkbox" id="crafty_dump_printable_check">Dump printable chars</label></td>
		</tr>
	</table>
	</div>
	<div class='crafty_crafter_section' id="crafty_packets_section">
	<table class="crafty_crafter_table">
		<tr>
	      <td colspan="2"><h3 class="crafty_h3">Packet Flags</h3></td>
		</tr>
		<tr>
        <td><label>
	        <input type="checkbox" id="crafty_flag_tcp_ack">TCP ACK</label></td>
        <td><label>
	        <input type="checkbox" id="crafty_flag_ack">ACK</label></td>
        <td><label>
        <input type="checkbox" id="crafty_flag_fin">FIN</label></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	    <tr>
        <td><label>
	        <input type="checkbox" id="crafty_flag_urg">URG</label></td>
        <td><label>
            <input type="checkbox" id="crafty_flag_syn">SYN</label></td>
        <td><label>
            <input type="checkbox" id="crafty_flag_xmas">XMAS</label></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	    <tr>
        <td><label>
            <input type="checkbox" id="crafty_flag_rst">RST</label></td>
        <td><label>
            <input type="checkbox" id="crafty_flag_ymas">YMAS</label></td>
        <td><label>
            <input type="checkbox" id="crafty_flag_push">PUSH</label></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2"><h3 class="crafty_h3">Packet Fragmentation</h3></td>
		</tr>
	    <tr>
	      <td colspan="2"><label>
	        <input type="checkbox" id="crafty_define_fragment_check" afield="crafty_fragment_offset">Define Fragmentation</label></td>
		</tr>
	    <tr>
			<td colspan="2">
			<div style="margin-left: 20px">
			<select id="crafty_fragment_amount" class="crafty_select">
				<option value="Custom">Custom Offset</option>
				<option value="-f">Normal Fragmentation</option>
				<option value="-x">More Fragmentation</option>
				<option value="-y">No Fragmentation</option>
			</select><br /><br />
			<input class="crafty_crafter_field" type="text" id="crafty_fragment_offset" placeholder="Fragment Offset">
			</div>
			</td></tr>
    </table>
	</div>
	<div class='crafty_crafter_section' id="crafty_save_section">
		<hr />
		<h3 id="crafty_save_command"></h3>
		<input type="text" class="crafty_crafter_field" id="crafty_command_title" placeholder="Command Title"/>
		<br /><br />
		<button class="crafty_button_small" id="crafty_confirm_save">Confirm</button>
		<button class="crafty_button_small" id="crafty_cancel_save">Cancel</button>
		<hr />
	</div>
	
	<div style="text-align: center;">
		<button class="crafty_button_small" id="crafty_build_button">Build</button>
		<button class="crafty_button_small" id="crafty_exec_button">Execute</button>
		<button class="crafty_button_small" id="crafty_save_command_button">Save</button>
		<button class="crafty_button_small" id="crafty_clear_options_button">Clear</button>
	</div>
	</div> <!-- End crafty_options_div -->
	
	<div class="crafty_crafter_shell">
		<h3 style="display: inline">Command: </h3><span id="crafty_current_command" style="color: white">None</span><br />
		<h3 style="display: inline">Status: </h3><span id="crafty_current_status" style="color: white">Not Running</span><br />
		<h3 style="display: inline">Process ID: </h3><span id="crafty_current_pid" style="color: white">None</span>
		<textarea id="crafty_shell" readonly>This area will update automatically...</textarea>
	</div>
</div>
</body>
</html>
