<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
define('__LOG__', $pineapple->directory . "/includes/log");
define('__CONFIG__', $pineapple->directory . "/includes/config");
define('__COMMANDS__', $pineapple->directory . "/includes/commands");
define('__SCRIPTS__', $pineapple->directory . "/includes/scripts");

$configs = array();
loadConfigData($configs);

if (isset($_POST['getWiFiInterfaces'])) {
	echo getWiFiInterfaces();
} else if (isset($_POST['startExec'])) {
	echo executeCommand($_POST['startExec']);
} else if (isset($_POST['small_exec'])) {
	// Get the target and command from the POST array
	$data = $_POST['small_exec'];
	
	// Swap the hard coded target with the new target
	$cmd = explode(" ", $data['cmd']);
	$cmd[2] = $data['target'];
	
	if (executeCommand(implode(" ", $cmd))) {
		echo true;
	} else {
		echo false;
	}
} else if (isset($_POST['getOutput'])) {
	echo getOutput();
} else if (isset($_POST['stopCommand'])) {
	echo stopCommand();
} else if (isset($_POST['getPID'])) {
	echo getPID();
} else if (isset($_POST['getCmd'])) {
	echo getCmd();
} else if (isset($_POST['saveCommand'])) {
	echo saveCommand($_POST['saveCommand']);
} else if (isset($_POST['getCommands'])) {
	echo implode(";", getCommands());
} else if (isset($_POST['deleteCommand'])){
	echo deleteCommand($_POST['deleteCommand']);
} else if (isset($_POST['installDepends'])) {
	echo installDepends();
}

function dependsInstalled() {
	$ret = exec(__SCRIPTS__ . "/check_depends.sh");
	if ($ret == "Installed") {
		return true;
	}
	return false;
}

function installDepends() {
	$ret = exec(__SCRIPTS__ . "/install_depends.sh");
	if ($ret == "Complete") {
		return true;
	}
	return false;
}

function getWiFiInterfaces() {
        $ret = array();
        $res = exec("ls /sys/class/net", $ret);
	if (!$res) {
		return false;
	}
        return implode("\n", $ret);
}

function executeCommand($command) {
	global $configs;
	$pid = exec($command . " > " . __LOG__ . " 2>&1 & echo $!");
	if ($pid == NULL) {
		return false;
	}
	$configs['pid'] = $pid;
	$configs['cmd'] = $command;
	saveConfigData($configs);
	return $pid;
}

function saveCommand($data) {
	try {
		$fh = fopen(__COMMANDS__, "a+");
		fwrite($fh, $data['title'] . ":" . $data['command'] . "\n");
		fclose($fh);
	} catch (Exception $e) {
		return false;
	}
	return true;
}

function getCommands() {
	$cmds = array();
	$fh = fopen(__COMMANDS__, "r");
	if ($fh) {
		while (($line = fgets($fh)) !== false) {
			array_push($cmds, trim($line));
		}
	}
	fclose($fh);
	
	if (count($cmds) > 0) {
		return $cmds;
	}
	return false;
}

function deleteCommand($dcmd) {
	$commands = getCommands();
	$savedCommands = array();
	if ($commands) {
		foreach ($commands as $cmd) {
			$tmp = explode(":", $cmd);
			if ($tmp[0] != $dcmd) {
				array_push($savedCommands, $cmd);
			}
		}
	}
	try {
		$fh = fopen(__COMMANDS__, "w+");
		foreach ($savedCommands as $scmd) {
			fwrite($fh, $scmd . "\n");
		}
		fclose($fh);
	} catch (Exception $e) {
		return false;
	}
	return true;
}

function getOutput() {
	return file_get_contents(__LOG__);
}

function getPID() {
	global $configs;
	if ($configs['pid'] == "") {
		return false;
	}
	$ret = exec("ps | grep hping3 | awk 'NR==1{print $5}'");
	if ($ret == "hping3") {
		return $configs['pid'];
	}
	return false;
}

function getCmd() {
	global $configs;
	return $configs['cmd'];
}

function stopCommand() {
	global $configs;
	$ret = exec("kill " . $configs['pid']);
	if ($ret != "") {
		return false;
	}
	$configs['pid'] = "None";
	$configs['cmd'] = "None";
	saveConfigData($configs);
	return true;
}

/* CONFIG FILE FUNCTIONS */

function saveConfigData($data) {
	$fh = fopen(__CONFIG__, "w+");
	if ($fh) {
		foreach ($data as $key => $value) {
			fwrite($fh, $key . "=" . $value . "\n");
		}
		fclose($fh);
		return true;
	}
	return false;
}

function loadConfigData(&$configs) {
	$config_file = fopen(__CONFIG__, "r");
	if ($config_file) {
		while (($line = fgets($config_file)) !== false) {
			$item = explode("=", $line);
			$key = $item[0]; $val = trim($item[1]);
			$configs[$key] = $val;
		}
	}
	fclose($config_file);
}

?>