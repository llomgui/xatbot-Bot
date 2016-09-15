<?php				     
	$commandsfile = json_decode(file_get_contents('./cmd.json', true), true);

	$cmdextension = $commandsfile['commands']['cmd-extension'];
	
	$cmdxs = $commandsfile['commands']['cmd-extension']; // short, using for creating commands.
	
	//made for testing by skyleter
?>
