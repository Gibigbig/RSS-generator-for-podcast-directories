<?php
//ini_set('display_errors',1);


//set your API keys
$google['key'] = '';
$google['cx'] = '';

// modify this function to suit your domain

function book($url){
	$o = str_replace('http%3A%2F%2Fxxxxxx.xxxx%2Fxxxx%2Fxxxx%2F','',$url);
	$o = str_replace('http://xxx.xxx/xx/xx','',$o);
	$o = str_replace('%2F&action=set&type=set','',$o);
	$o = str_replace('%20',' ',$o);
	
	$o = rtrim(ucwords($o),'/');
	
	return $o;
}


// You probably won't need to edit below this

function remote_filesize($url) {
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$fp = @fopen($url, 'rb')) {
        return false;
    }
    //echo print_r($http_response_header);
    if (
        isset($http_response_header) &&
        preg_match($regex, implode("\n", $http_response_header), $matches)
        
    ) {
        $size =  (int)$matches[0];
        $data['size'] = $size;
        $data['date'] = $http_response_header[1];
        return $data;
    }
    return strlen(stream_get_contents($fp));
}


function name($url){
	$o = str_replace('.mp3','',str_replace('%20',' ',$url));
	return $o;
}



function image($book){
	$o = str_replace(' ','+',$o);
	
	// Create a stream
	$opts = [
	    "http" => [
	        "method" => "GET",
	        "header" => "Accept-language: en\r\n" .
	            "Cookie: foo=bar\r\n"
	    ]
	];
	
	$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
	
	$json = file_get_contents('https://www.googleapis.com/customsearch/v1?key='.$google['key'].'&cx='.$google['cx'].'&q='.$book.'+book&searchType=image&fileType=jpg&imgSize=medium&alt=json', false, $context);
	$arr = json_decode($json,true);
	
	$url = $arr['items'][0]['image']['thumbnailLink'];
	
	//echo print_r($arr);
	
	return $url;
}




$o = '';$action = '';
if($_REQUEST['action'] == ''){
	
	echo '<form method="get" action="feed.php">Directory<input name="folder" /><input name="action" value="set" type="hidden" /><input type="submit" /></form>';
	
}  else {



/*
	Runs from a directory containing files to provide an
	RSS 2.0 feed that contains the list and modification times for all the
	files.
*/

$feedDesc = "Feed for the my audio files in some server folder";
$feedURL = $_REQUEST['folder'];
$feedName = book($feedURL);
$feedBaseURL = $_REQUEST['folder']; // must end in trailing forward slash (/).
$allowed_ext = ".mp4,.MP4,.mp3,.MP3";

$o = '<?xml version="1.0"?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>'. $feedName.'</title>
		<link>'.$feedURL.'</link>
		<description>'.$feedDesc.'</description>
		<atom:link href="http://gogglesoptional.com/bloopers" rel="self" type="application/rss+xml" /> 
 
    <image>
      <url>'.image($feedName).'</url>
      <title>'. $feedName.'</title>
      <link>'. $feedURL.'</link>
    </image>';
$files = array();


/*

use this instead if for local directories

$dir=opendir("./");
while(($file = readdir($dir)) !== false)  
{  
	$path_info = pathinfo($file);
	$ext = strtoupper($path_info['extension']);
	if($file !== '.' && $file !== '..' && !is_dir($file) && strpos($allowed_ext, $ext)>0)  
	{  
		$files[]['name'] = $file;  
		$files[]['timestamp'] = filectime($file);
	}  
}  
closedir($dir);

*/

///$html = file_get_contents($feedURL);
$input = @file_get_contents($feedURL) or die("Could not access file: $url");



$d = preg_split("/<\/a>/",$input);
foreach ( $d as $k=>$u ){
    if( strpos($u, "<a href=") !== FALSE ){
        $u = preg_replace("/.*<a\s+href=\"/sm","",$u);
        $u = preg_replace("/\".*/","",$u);
        if($u != '../'){
        	//$files[]['title'] = str_replace('.mp3','',str_replace('%20',' ',$u));
        	$files[]['name'] = $u;
        }
    }
}

//echo print_r($files);

// natcasesort($files); - we will use dates and times to sort the list.
for($i=0; $i<count($files); $i++) {
	if($files[$i] != "index.php") {
          if (!empty($files[$i]['name'])) {
          	
          	$data = remote_filesize($feedBaseURL . $files[$i]['name']);
          	
          	
		$o .=  "<item>";
		$o .=  "<title>". name($files[$i]['name'])  ."</title>";
		$o .=  "<link>". $feedBaseURL . $files[$i]['name'] . "</link>";
		$o .=  "<guid>". $feedBaseURL . $files[$i]['name'] . "</guid>";
		$o .= '<enclosure url="'. $feedBaseURL . $files[$i]['name'].'" length="'.$data['size'].'" type="audio/mpeg"/>';

		$o .= "		<pubDate>" . $data['date'] ."</pubDate>\n";
		$o .= '<description>Filler</description>';
		$o .=  "</item>"; 
	  }
	}
}

$o .= '</channel>
</rss>';

 }
 
 
 if($_REQUEST['type'] == ''){
 	//echo link
 	echo '<html><head> <meta name="viewport" content="width=device-width, initial-scale=1.0" /></head><body>';
 echo "<textarea style='width:100%;height:50%'>http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."&type=set</textarea><br/><br/><a href='/feed.php'>Home</a> ";
 	
 } else {
 	header('Content-type: text/xml');
	 echo $o;
 }
 
 ?>
