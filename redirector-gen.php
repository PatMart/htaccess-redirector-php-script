<?
/*


*Very basic* script to convert csv input like:
http://oldomain.com/old-page.php,newpage.com

double check all results first. 
this is only meant to be quick and small.
no error checking etc is done.
no user input - just edit the source code.

 */




###########EDIT THIS:
	$line = __LINE__ + 1;
	$data = '
		http://oldsite.com/foo-stuff.htm,http://newsite.com/foo/
		http://oldsite.com/foobarrrrr-stuff.htm,http://newsite.com/foobarrr/
		http://oldsite.com/order-page.htm,http://newsite.com/shopping/
		http://oldsite-newsite.com/index.php,http://newsite.com/
		http://oldsite.com/gallery.php?gallery=123,http://newsite.com/gallery/123/
		http://oldsite.com/page.php?slug=short,http://newsite.com/a-long-page-for-neatness/

		'; // csv. each line should be "oldurl,newurl". 
	$_delim = ',';


define("USE_PARSE_URL_INSTEAD_OF_MANUALLY_STRIPPING_HOSTNAME", true);

		// this function gets everything after the domain from a URL. It assumes it will be given a valid full url.
		function strip_domain($theFullUrl) {


		if (USE_PARSE_URL_INSTEAD_OF_MANUALLY_STRIPPING_HOSTNAME) {

			$url_parts = parse_url($theFullUrl);
			if ($url_parts === null) {

				die("Sorry, invalid request for parse_url - $theFullUrl");

			}

			$path_and_query = $url_parts['path'];

			// if has query string, add it
			if ($url_parts['query']!='') {
				$path_and_query .= "?" . $url_parts['query'];
			}




			$stripped_domain = $path_and_query;

		}
		// or, old manual way. Handy in certain circumstances 
		else {

			// get $olddomain
			$stripped_domain = str_replace($olddomain . '/'					,$olddomain,$theFullUrl); // could cause bug if you have http://olddomain.com/olddomain/test.htm 
			$stripped_domain = str_replace('http://www.' . $olddomain,''	,$stripped_domain);
			$stripped_domain = str_replace('http://' . $olddomain,''		,$stripped_domain);


		}
		return $stripped_domain;
		}


	// Some obvious duplication of code here. But unless you're doing 10's of thousands of lines it won't affect speed!
	function getLongestLengthFromArrayIndex($data,$index) {

		global $_delim;
		$longest = 0;
		foreach($data as $line) {


			$new = explode($_delim, $line);
			$new = $new[$index];

		

			$len = strlen($new);
			if ($len > $longest) {

				$longest = $len;

			}

		}

		return $longest;

	}


	$_rewrite = 'RewriteRule ^';

	$csvDataAsArrayOfLines = (explode("\n",trim($data)));

	// get longest length just so we can str_pad it nicely
	$longestNewDomain = getLongestLengthFromArrayIndex($csvDataAsArrayOfLines,1) + 3;
	$longestOldDomain = getLongestLengthFromArrayIndex($csvDataAsArrayOfLines,0) + 3 + strlen($_rewrite); // actually, isnt the correct calculation 
	foreach($csvDataAsArrayOfLines as $d) {
		$d = explode($_delim,trim($d));


		$oldUrl = trim($d[0]);

	
		$old = strip_domain( $oldUrl , $oldDomain );
		$new = trim($d[1]);



		if ($old!='' && $new != '') {



			$output.="\n" . 
					str_pad("      " . $_rewrite .$old . '$', $longestOldDomain) . 
					" "  . 
					str_pad($new, $longestNewDomain) .
				   ' [R=301,L]';
		}

	}


	echo "<h1>CSV .htaccess Redirector</h1>

		<p>Please edit the source code of this file - edit \$data on line $line</p>

		

		<p>CSV Data:</p>

<pre style='background: #efefef; padding: 5px;'>" . htmlentities($data) . "</pre><br />";

$rows = 8 + count(explode("\n", $output));

	echo "<textarea style='width:100%;' onfocus='this.select()' onmouseover='this.focus()' onclick='this.focus();this.select()'  rows='$rows'>
<IfModule mod_rewrite.c>
      RewriteEngine on
      RewriteBase /
$output

</ifModule>

</textarea>

	(Edit <code style='background: #efefef'>RewriteBase /</code> to whatever subfolder your site is in)";


