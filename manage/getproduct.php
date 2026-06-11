<?php
// Source - https://stackoverflow.com/a
// Posted by sepehr, modified by community. See post 'Timeline' for change history
// Retrieved 2025-12-19, License - CC BY-SA 3.0

 

$html = file_get_contents('https://www.payalsinghal.com/search?&q='.$_GET['q']);

// You need to check if it's matched before assigning 
// $price[1]. Anyway, this is just an example.
//echo $html;
$arrpattern=array('data-wlh-image','data-wlh-link','data-wlh-price');
// finalise the regular expression, matching the whole line

for($x=0;$x<count($arrpattern);$x++){
$pattern = "/^.*".$arrpattern[$x].".*\$/m";

// search, and store all matching occurences in $matches
if (preg_match_all($pattern, $html, $matches))
{
   echo implode("\n", $matches[0]);
}
}

?>