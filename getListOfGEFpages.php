<?php

// cycles through Global Environment Facility page that lists papers extracting their urls.
// Creates a text file with one link per page
// July 31, 2016
// http://simplehtmldom.sourceforge.net/manual.htm#section_quickstart
include "simple_html_dom.php";

$gefbase = "https://www.thegef.org/gef/publist"; // where GEF's list of pages is
$maxpagestofetch = 11;
// open file

$f = fopen("GEFlinks.txt","w");

$urls = array(); // master array of links

// cycle through known pages
for ($page=1; $page < $maxpagestofetch; $page++){
	// get the page
	
	if ($page != 1) { // first page has no number.
		$html = file_get_html($gefbase . "?page=" . $page);
		//print "<br>file name: " . $gefbase . "?page=" . $page;
	}
	else {
		$html = file_get_html($gefbase);
		//print "<br>file name: " . $gefbase;
	}

	// get the container div
	$container = $html->find('div[id=default-content]');
	print "<br>count: " . count($container); 
	
	// get the links to talks
	foreach($container[0]->find('a') as $element) {
		$href = $element->href;
	
			// add the link to the array
			$sp = strpos($href, "/gef/node");
			if ($sp === 0){
				//print "<br><i>gefnode: " . $href . " pos=" .$sp . "</i>";
				// check for dupes
				if ( in_array($href,$urls) == false){ 
					$urls[] = $href;
					print '<br>' . $href ;
				}
			}
		}
	
	print "<br>Page: $page. Number of links: " . count($urls) . "<br>";
}
// write the urls out to a file
for ($a = 0; $a < count($urls); $a++){  
	fwrite($f,"https://www.thegef.org" . $urls[$a]);
	if ($a != count($urls) - 1){
		fwrite($f,"\n");
	}
}     

fclose($f);

?>