STATSIGNITER
============
How many lines of code is your project? 
With StatsIgniter you can scan your application and count them in an easy and simple way.

Demo:
http://www.destinomultimedia.com/demos/statsigniter

IMPORTANT NOTES
=================
You need to edit /application/config/autoload.php and load 'url' and 'file' helpers and 'StatsIgniter' library.

Edit /application/controllers/stats.php to config library options.

IMPORTANT FILES
================
*.htaccess
----------
You need to enable mod_rewrite on your webserver. Change your_app by your app name.

COMMENTS.
=========
The analyze.php view load some js and css files. The .js and .css files are contained on /assets/ folder. 
If you wanto to move this folder, you need to edit /application/views/stats/analyze.php with the new path.


