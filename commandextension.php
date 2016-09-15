<?php				     
	$commandsfile = json_decode(file_get_contents('./config.json', true), true);

	$cmdextension    = $commandsfile['bots']['cmd-extension'];
	
	$cmdxs = $commandsfile['bots']['cmd-extension']; // short, using for creating commands.
	
	//made for testing by skyleter
?>
