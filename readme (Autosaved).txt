.htaccess Redirector PHP Script.

Need to redirect a bunch of URLs? Got those urls and the new locations in CSV format? This is for you. Only run this script in a development area, never for a live site.

I recommend using a site spider service such as sitemapdoc.com to get a list of all current urls (This is assuming your site is smallish, static urls).

Then append to each line ",http://newurl.com/"


For example to move




Only meant to be used as a very quick tool.

############
############
FULL OF BUGS.
Only use as a quick tool. Only up on github so I can grab it again.
Handy when you have a list of old URLs and are moving them to new urls. 
############
###########

Don't use on production sites. full of potential bugs.


Use something like http://www.sitemapdoc.com/Default.aspx to generate a textlist of old urls, then add ',http://newsite/newpage' to each line.
