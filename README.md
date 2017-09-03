# RSS-generator-for-podcast-directories
A Simple RSS generator to make RSS files in the default Nginx "autoindex on" directories.

# Why?
Because this actually doesnt really exist yet, and I found myself downloading a lot of podcasts and audiobooks via Transmission web GUI and a lot of uploaders now started splitting and uploading by chapter. Thats great and all, but its a bit difficult to import each chapter individually when your book has 90+. So I decided to make use of Downcast's RSS features (the app I use for podcasts and audiobooks). This script will take the nginx default list of files when *autoindex* is turned on, and spit out an RSS feed readable by Downcast. Each "episode" would be a chapter.

I even integrated google's custom search engine to pull the Book or Podcast's image automatically if you have a google custom search engine API key and CX code. (Free for up to 100 queries per day)

# Requirements
* PHP 7.0

# Optional
* Nginx
* Google custom search engine API keys 

# How to
Just have php installed. No need for the files to be on the same server. Remote RSS generation is possible.
Have a nginx (or any list of download links in html) directory ready and put the URL in the directory field when you run *feed.php*, hit "submit", the script will spit out an RSS link which you can paste into Downcast's Import Field.

Thats it really

You may also need to update this function to match your domain, i'll prolly fix this with regex later:

```
function book($url){
	$o = str_replace('http%3A%2F%2Fwla.fun%2Ffeed%2Fmedia%2F','',$url);
	$o = str_replace('http://wla.fun/feed/media/','',$o);
	$o = str_replace('%2F&action=set&type=set','',$o);
	$o = str_replace('%20',' ',$o);
	
	$o = rtrim(ucwords($o),'/');
	
	return $o;
}
```
