<?php
#
# grojsus (v.3.1 - 13.06.2003) is advanced pagination function
#
# url: http://zone.ee/internetu/php/grojsus/
#
# Parameters:
#
#	$array - array which splits to pages (can be array or array/table length too -- 
#            count(file("name.txt")) or "select count(*) from table")
#	$page - current page to show; this should be with same name like defined with $pagevar
#	$itemsperpage - items per page
#	$pageurl - which url to use as links (can contain variables) if empty then equal with Php_self
#	$pagevar - pager variable name in url (&page=123)
#	$keepurlvar - if its true then current Query_string stay
#	$options - "SE" (start and end links)
#	$maxlen - how many links to show, if -1 then all, default 10 (should be at least 4)
#	$style - how to show navigation bar: 0 - simply page numbers, 1 - items range (1-10)
#	$debug - returns debugging info or not
#
# Returns:
#   array where:
#   [0] - if is_array($array) then contains all items of current page, else returns false
#   [1] - navigation bar with links
#   [2] - debugging info (only if $debug = true) 
#   [3] - items starting number on current page
#   [4] - items ending number on current page
#   [5] - items per page (same as input parameter $itemsperpage)
#
# History :
#
#	13.06.2003 - Grojsus 3.1 Now it's much better than previous versions! Very flexible
#                function, you can give either array or total pages number, items per page
#                and even max. links to show at once. Total 10 parameters [7 have default]
#                value. Works very well with text files or databases. Its even better than
#                PHPBB2 [with phpBB2 pager you cant give array or links number as argument]
#                or Google [cant go to start or last page] one's ;-)
#
#	31.12.2002 - Grojsus 2. Now it takes array, current page, items per page, url variable
#                and style as function arguments and returns array with current page items
#                and navigation bar. Function using is much simpler.
#
#	01.01.2002 - I made first pagination for one forum, then it wasn't yet function,
#                code snippet instead...
#
# Bugs :
#	all fixed! actually, not all. there are one "bug", when there are more paginations
#	on page then first ones handles $QUERY_STRING incorrectly, because first pagers haven't
#	got yet following $QUERY_STRING value. (if i understand correctly this case of course)
#
#	and if $QUERY_STRING is empty then grojsus adds to it 2 variables with same name
#   (?p=1&p=8), but this is more cosmetic than serious bug...
#
# CopyLeft (C) 2002-2003 Lauri Kasvandik, lauri_k@mail.ru
#
#	This program is free software; you can redistribute it and/or modify it under the 
#	terms of the GNU General Public License as published by the Free Software Foundation; 
#	either version 2 of the License, or (at your option) any later version.
#
#	This program is distributed in the hope that it will be useful, but WITHOUT ANY 
#	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
#	PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
#	YOU MUST RETAIN COPYRIGHT NOTICE!
#
#	http://www.gnu.org/copyleft/
#
function grojsus($array,$page,$itemsperpage,$pageurl='',$pagevar='page',$keepurlvar=true,$options='E',$maxlen=10,$style=1,$debug=false)
{
	global $PHP_SELF, $QUERY_STRING;
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$QUERY_STRING=$_SERVER['QUERY_STRING'];

	if (!isset($page)) {
		$page = 0;
	}

	$pageurl = $pageurl == '' ? $PHP_SELF : $pageurl;
	is_array($array) ? $total_items = count($array) : $total_items = $array;

	# items start and end on current page
	$start = $page * $itemsperpage;
	$end = $start + $itemsperpage > $total_items ? $total_items : $start+$itemsperpage;

	# makeing return array
	if (is_array($array)) {
		for ($i=$start;$i<$end;$i++)
		{
			$ret_array[] = $array[$i];
		}
	}
	else {
		$ret_array = false;
	}


	# if there are more items than goes to one page 
	# then we make navigation bar with links
	if ($total_items > $itemsperpage)
	{
		$links_total = ceil($total_items / $itemsperpage);

		/*
		 little meditation ;-)
		 
		 i wish to make that there are not at once all links available (only $maxlen,
		 or fewer) and when click to edges of links and there are mucher links then
		 links moves to forward or backward.

		 suppose:
		 maxlinks ($maxlen) = 10, pages ($links_total) = 30, current page ($page) = 10
		 howto figure out where links start and end?

		 elementary! $div=$maxlen / 2,
		 begin = $page - $div, (or 0 if its < 0)
		 end = $page + $div (or $total_links if its bigger)

		 $links_start = $page - $d < 0 ? 0 : $page - $d;
		 $links_end   = $page + $d > $links_total ? $links_total : $page + $d;

		 is it really so simple?
		 hmm, not exactly: if current page is 0 and other variables are same then
		 there are only 5 links: should add remainder and add it to links_end
		 and check out if there are still fewer links than maxlen then should 
		 subtract links_start (of course, only when it is not yet in beginning).
		*/

		# now we calculate which links to show:
		# first links_start
		$d = floor($maxlen / 2);
		if ($page - $d < 0)	{
			$links_start = 0;
			$remainder = abs($page -$d);	// we add it to $links_end
		}
		else {
			$links_start = $page - $d;
			$remainder = 0;
		}

		# and then links_end:
		if ($page + $d + $remainder > $links_total)	{
			$links_end = $links_total;
		}
		else {
			$links_end = $page + $d + $remainder;
		}

		# and one thing more : if there are still fewer links then maxlen
		# then we move start to little left or if it is already in start then
		# we keep it along. (heh, and this is still beginning ;-)
		if ($links_end - $links_start < $maxlen)
		{
			$links_start = $page - $d < 0 ? 0 : $links_start - ($maxlen-($links_end - $links_start));
			$links_start = $links_start < 0 ? 0 : $links_start;
		}

		if ($debug==true)
		{
			$cp=$page+1;
			$ls=$links_start+1;

			$dbg .= '<br>total items: '.$total_items;
			$dbg .= '<br>items per page: '.$itemsperpage;
			$dbg .= '<br>total pages:'.$links_total;;
			$dbg .= '<br>current page: '.$cp;
			$dbg .= '<br>maxlinks: '.$maxlen;
			$dbg .= '<br>remainder:' . $remainder;
			$dbg .= '<br>first page:'.$ls;
			$dbg .= '<br>last page:'.$links_end;
			$dbg .= '<br>start link: '.$b = stristr($options,'s') ? 'true' : 'false';
			$dbg .= '<br>end link: '.$b = stristr($options,'e') ? 'true' : 'false';
		}
		else
		{
			$dbg='';
		}
	
		if ($maxlen == -1)
		{
			$links_start = 0;
			$links_end = $links_total;
		}
		for ($i=0;$i<$links_total;$i++)
		{
			#print '-'.$i;

			$no1 = $i * $itemsperpage + 1;	# pageno (from)
			$no2 = $no1 + $itemsperpage -1;	# second page no (to)
			$no2 > $total_items ? $no2 = $total_items : '';

			//if ($page == $i){ $navbar .= "<b>"; }
			# now is time to generate url...
			$ymark = ($page == $i) ? 'selected' : '';
			//echo $QUERY_STRING."<BR>";
			if ($keepurlvar==true && strlen($QUERY_STRING))
			{
				$QUERY_STRING = str_replace("&$pagevar=[[:digit:]]+", '', $QUERY_STRING);
				$QUERY_STRING = preg_replace('/&p=(\d+)/', '', $QUERY_STRING); 
				if (strstr($pageurl,'?')){ $xmark = '&'; }
				else { $xmark = '?'; }
				$url = "<option value='".$pageurl.$xmark.$QUERY_STRING."&".$pagevar."=$i' ".$ymark.">";
			}
			else
			{
				$pageurl = str_replace("&$pagevar=[[:digit:]]+", '', $pageurl);
				$pageurl = preg_replace('/&p=(\d+)/', '', $pageurl);
				$xmark = strstr($pageurl,'?') == true ? '&' : '?';
				$url = "<option value='".$pageurl.$xmark.$pagevar."=$i' ".$ymark.">";
			}
			//echo $QUERY_STRING."<BR>";

			$navbar .= $url;
			$navbar .= ($i+1);
			//$style == 0 ? $navbar .= $i: '';
			//$style == 1 ? $navbar .= $no1."-".$no2 : '';
			$navbar .= "</option>";


			$navbar .= "\n";
			
		}

		
		//$navbar="<li><select class='form-control' style='width:70px;float:left' name='pag' onchange='document.location.href=this.value'>".$navbar."</select></li>";
		$navbar="<select class='form-control' style='width:70px;' name='pag' onchange='document.location.href=this.value'>".$navbar."</select>";

		# we show start link only when we cant see first link and if options contain 'S' char

		if($page == 0){
			//$navbar = $navbar.'<li><a href="'.$pageurl.$xmark.$QUERY_STRING.'&'.$pagevar.'=1"><i class="fa fa-angle-double-right"></i> </a></li>' ;
		}else if ($page <  ($links_total-1)){
			//$navbar = $navbar.'<li><a href="'.$pageurl.$xmark.$QUERY_STRING.'&'.$pagevar.'='.($page+1).'"><i class="fa fa-angle-double-right"></i> </a></li>';
		}

		if ($page >= 1)
		{
			//$navbar = '<li><a href="'.$pageurl.$xmark.$QUERY_STRING.'&'.$pagevar.'='.($page-1).'"><i class="fa fa-angle-double-left"></i> </a></li>'. $navbar ;
		}
		//$navbar = '<ul class="pagination">'.$navbar.'</ul>';
	} 

	return (array($ret_array,$navbar,$dbg,$start,$end,$itemsperpage));
}

# if this file is not included by any other page then we show this message...
basename(__FILE__) == basename($PHP_SELF) ? print 'this file contains advanced pagination function named Grojsus. Take a look <a href="naide.php">usage example with textfiles</a> or <a href="db.example.php.txt">with databases</a>' : '';

?>