# RSS-generator-for-podcast-directories
A Simple RSS generator to make RSS files in the default Nginx "autoindex on" directories.

#Why?
Because I found myself downlaoding a lot of podcasts and audiobooks via Transmission GUI and a lot of uploaders now start uploading my chapter. Thats great and all, but its a bit difficult to import each chapter individually when your book has 90+. So I decided to make use of Downcast's RSS features (the app I use for podcasts and audiobooks). This script will take the nginx default list of files when autoindex is turned on, and spit out an RSS feed readable by Downcast. Each "episode" would be a chapter.

I even integrated google's custom search engine to pull the Book or Podcast's image automatically if you have a google custom search engine API key and CX code. (Free for up to 100 queries per day)

#Requirements
* PHP 7.0

#Optional
* Nginx
* Google custom search engine API keys 

#How to
Just have php installed. No need for the files to be on the same server. Remote RSS generation is possible.
Have a nginx (or any list of download links in html) directory ready and put it in the directory field, hit "submit", the script will spit out an RSS link which you can paste into Downcast's Import Field.
