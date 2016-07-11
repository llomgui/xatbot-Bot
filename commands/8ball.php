<?php

${'8ball'} = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

	if (!isset($message[1]) || empty($message[1])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !8ball [question]', $type, true);
	}
	
	$response = [
		"It is certain", "It is decidedly so", "Without a doubt", "Yes, definitely", "You may rely on it",
		"As I see it, yes", "Most likely", "Outlook good", "I think, Yes", "Signs point to yes", "Reply hazy try again",
		"Ask again later", "Better not tell you now", "Cannot predict now", "Concentrate and ask again",
		"Don't count on it", "My reply is no", "My sources say no", "Outlook not so good", "Very doubtful", "Out come looks good!",
		"Out come looks bad!", "How would I know that?", "Could you repeat the question?", "Of course!  How could it not be?", 
		"Not a chance!", "Don't count on it!", "All sources point to no!", "All sources point to yes!", "Don't bet too much money on it!",
		"Definately!", "Definately not!", "Not a chance!", "This is an 8-ball, not a crystal ball...!", "Don't Hold Your Breath!", "Yes!", "No!", 
		"Fat Chance!", "hold on, gotta take a ****", "I JUST FARTED!!", "damn I gotta take a piss...BAAADDD!!!", "Has anyone seen my other ball?", 
		"Like I would know!", "yes???", "no???", "In your wildest, craziest, bodacious, helacious dream", "Only if I give a damn...and I don't", 
		"Only if you give a damn", "LaLaLaLa...what? did you say something?", "yes yes yes yes yes yes yes", "no no no no no no no", 
		"I know you love me ;)", "Go ask the 9-ball!", "Do I look like a cue ball?", "looks pretty good!", "looks pretty bad!", 
		"Ofcourse!  How could it not be?", "How the hell would I know that?", "Not a chance on earth!", "Shouldn't you know this?...Sheesh!", 
		"You're Dumb, go away", "Both!", "Alright, sounds good enough to be right..", "My Magic 8-Ball shows the letters...'Y, e, and s'", 
		"My Magic 8-Ball shows the letters...'N and o'", "My Magic 8-Ball shows a Lamah Screaming 'Noooo!'", 
		"The Magic 8-Ball shows a picture of a little fairy, trying to teach a little kid how to fly and the little kid doesn't want to lear.....oops wrong story! NO!", 
		"This one will have to go to the judges...and...I'm sorry..The judges have ruled No to that question", 
		"This one will have to go to the judges...and.....The judges have gave me a thumbs up to that question!", "I'm an 8-ball not a pool stick!", "
		ummmmmmm....whatever you say :)", "uhhhhh....sure!", "shut up", "I'm sick of questions! I'm gonna barf all over the person that asks me another question!", 
		"Let me ask YOU a question: Don't you have sumthin better to do than sit around and talk to an imaginary 8-ball over the internet?", 
		"f√ck no", "uhhh...hmmm...no??", "uhhh...hmmm..yes??", "sure! uh huh! whatever!", "I'm running out of answers", "I think you've used up all my answers", 
		"Slim chance...", "Good question...I need time to think...try back later...", "I CAN'T DO IT CAPTAIN! I DON'T HAVE THE ANSWER!", "HAHAHAHA! No!", 
		"yes.", "most definitely.", "never!!!", "no.", "maybe.", "probably.", "it's likely.", "cannot predict now - try again", 
		"arghhh, watta i look like a fortune teller?", "NOT IN A MILLION YEARS!!!!", "my sources say 'are you crazy? Of course'!", "my sources say NO", 
		"my sources say YES", "i'm an 8-ball not a fortune teller for gods sake.", "thats a tough question, i don't know.", "NO!! HEAVEN'S NO!!!", 
		"yeah right.", "hmmmm.... I think so. :) but others may not.", "oF cOUrsE, you mean you had to ask me to figure that one out? Some people, geesh!", 
		"in your dreams!", "of course...", "what do you think?", "it's a 50% chance.", "100% POSITIVE YES!!!!!!", "100% POSITIVE NO!!!!!!", 
		"what part of NO dont you understand???? the n. or the o.????", "cannot think now, i'm constipated.", "hmmmm... i think so.......", "you'll find out yourself!!!", 
		"YES, OF COURSE!", "i don't know, ask again...", "HELLZ no!!", "Can't help you with that.", "f√ck if I know!", "Its your lucky day!", 
		"Go ask a psychic!!", "Shut-up! Im busy right now!!!", "Considering the circumstances... YES!", "Considering the circumstances... NO!", 
		"uhhhhh.. I guess so, but I could be wrong, im just a dumb b0t", "Yupperz!", "please dont ask hard questions like that :(", 
		"I'm on the can.. ask again later..", "Lose 20 lbs and shave off that gotee and its a possibility...", "Gimme 50 bucks and I'll say yes. ;)", 
		"Only if you believe so", "ummm...thats a toughy...hmmm..no??", "Don't bet on it", "I would say yes, but I've got a guy with a gun to my head telling me to say no :(", 
		"I would say no, but I've got a guy with a gun to my head telling me to say yes :(", "Hold on...I'm takin a piss..ask again later"
	];
	$bot->network->sendMessageAutoDetection($who, $response[array_rand($response, 1)], $type);
};