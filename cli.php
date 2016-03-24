<?php
/**
 * @Author: Nate Bosscher (c) 2015
 * @Date:   2016-03-24 12:19:14
 * @Last Modified by:   Nate Bosscher
 * @Last Modified time: 2016-03-24 13:09:58
 */

namespace BlueGiraffeSystems;

require_once __DIR__ . "/src/sanitizer.php";

echo "PHP HTML Style Sanitizer 1.0\n";
echo "by Nate Bosscher 2016\n";
echo "\n";

// create tmp file
$tmpfname = tempnam("/tmp", "html-remove-style-tmp-file");

// open stdin
$stdin = fopen ("php://stdin","r");

for(;;){
	// notify user
	echo "\nPress <enter> to sanitize the text in your clipboard, 'q' + <enter> to quit.\n";
	echo "Waiting... ";

	// block until enter press
	$c = fgets($stdin);

	// check for end of script
	if(trim($c) == "q")
		break;

	// send clipboard data to tmpfile
	exec('osascript -e \'the clipboard as «class RTF »\' | \
	    perl -ne \'print chr foreach unpack("C*",pack("H*",substr($_,11,-3)))\'' . " > $tmpfname");

	// convert to html
	exec("textutil -convert html -output $tmpfname $tmpfname");

	// get clipboard data from tmp file
	$contents = file_get_contents($tmpfname);

	// check that we got html
	if(strpos($contents, "<body>") === false){
		echo "ERROR: Content was not HTML\n";
	}else{

		// remove html extras from textutil
		$contents = substr($contents, strpos($contents, "<body>") + strlen("<body>"));
		$contents = substr($contents, 0, strpos($contents, "</body>"));

		// write results to tmp file
		$handle = fopen($tmpfname, "w");
		fwrite($handle, Sanitizer::htmlRemoveStyle($contents));
		fclose($handle);

		// write results to clipboard
		exec("echo $tmpfname | pbcopy");

		echo "SUCCESS\n";
	}
}

// cleanup
unlink($tmpfname);
fclose($stdin);