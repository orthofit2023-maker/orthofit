<?php
include("manage/db5conn.php");
header('Content-type: application/xml');
libxml_use_internal_errors(true);
$myXMLData =
'<?xml version="1.0" encoding="UTF-8"?>
<urlset
	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
			
<url>
	<loc>https://www.'.$GLOBALS['serverdomain'].'</loc>
	<lastmod>'.date("Y-m-d").'T10:44:00+00:00</lastmod>  
	<changefreq>always</changefreq>
	<priority>1.00</priority>
</url>
';
$sql="select t.typevalue, p.produrl from ccd9products p join ccd9prod2cat p2c on p2c.prodid=p.prodid join ccd9types t on p2c.catid=t.typeid where p.prodstatus='1' group by p.prodid order by CAST(t.typevalue1 AS UNSIGNED ), p.entrydate desc";
$result = $mysqli->query($sql); $oldcat='';
while($listing = $result->fetch_array()){
if($oldcat!=$listing['typevalue']){
$myXMLData .='
<url>
	<loc>https://www.'.$GLOBALS['serverdomain'].'/'.$listing['typevalue'].'</loc>
	<lastmod>'.date("Y-m-d").'T10:44:00+00:00</lastmod>  
	<changefreq>Always</changefreq>
	<priority>0.80</priority>
</url>';
}
$myXMLData .='
<url>
	<loc>https://www.'.$GLOBALS['serverdomain'].'/'.$listing['typevalue'].'/'.$listing['produrl'].'</loc>
	<lastmod>'.date("Y-m-d").'T10:44:00+00:00</lastmod>  
	<changefreq>Always</changefreq>
	<priority>0.64</priority>
</url>';
	$oldcat=$listing['typevalue'];
}

$sql="select p.title, p.pageurl from ccd9pages p where p.status='1' and p.iscart='1' group by p.pageid";
$result = $mysqli->query($sql);  
while($listing = $result->fetch_array()){
$myXMLData .='
<url>
	<loc>https://www.'.$GLOBALS['serverdomain'].'/blog-post/'.$listing['pageurl'].'</loc>
	<lastmod>'.date("Y-m-d").'T10:44:00+00:00</lastmod>  
	<changefreq>Always</changefreq>
	<priority>0.64</priority>
</url>';
}

$sql="select p.title, p.videoid from ccd9videos p where 1=1 group by p.id";
$result = $mysqli->query($sql);  
while($listing = $result->fetch_array()){
$myXMLData .='
<url>
	<loc>https://www.'.$GLOBALS['serverdomain'].'/video-post?videoid='.$listing['videoid'].'</loc>
	<lastmod>'.date("Y-m-d").'T10:44:00+00:00</lastmod>  
	<changefreq>Always</changefreq>
	<priority>0.64</priority>
</url>';
}

$myXMLData .='
</urlset>
';

$xml = simplexml_load_string($myXMLData);
if ($xml === false) {
    echo "Failed loading XML: ";
    foreach(libxml_get_errors() as $error) {
        echo "<br>", $error->message;
    }
} else {
    print_r($myXMLData);
}
?>