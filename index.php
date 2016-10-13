<?php
/**
 * Created by PhpStorm.
 * User: riese
 * Date: 10/12/2016
 * Time: 7:36 PM
 * Simple example of scraping a website for an image and redisplay it after modification
 */
?><!doctype HTML>
<html>
<head>
    <title>Example Application</title>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
    <style>
        img{
            display:inline-block;
        }
    </style>
</head>
<body>
<div class="container">
<form method="post">
    <label>URL</label><input type="text" id="urlfield" name="url" oninput="validateUrl(this);" value="<?php echo isset($_POST['url'])? $_POST['url'] : "" ?>"/><label id="errorLabel" style="opacity:1"></label><br/>
    <input type="submit" name="findImages" id="submitButton" value="Find Images" disabled="true"/>
</form>
    <script>
        function validateUrl(target)
        {
            /*
            A not-so-accurate url validator
            for demonstrating ability to use regular expressions and javascript
             */
            var matches = target.value.match(/(https*\:\/\/)*(?:(\w+\.)*\w+)+(\/\w*)*(\?([A-z0-9\-%=&]+)*)?/g);
            if(matches != null && matches[0] == target.value)
            {
                $('#errorLabel').animate({opacity:1}, 500);
                $('#errorLabel').text("URL OK");
                $("#submitButton").prop('disabled',false);
            }
            else
            {
                $('#errorLabel').text("Invalid URL");
                $('#errorLabel').animate({opacity:1}, 500);
                $("#submitButton").prop('disabled',true);
            }
        }

        validateUrl(document.getElementById("urlfield"));
    </script>
<?php

//copy-paste from stackoverflow to get absolute url
function rel2abs( $rel, $base )
{
    if(substr($rel, 0, 2) == "//")
        return ("http://" . substr($rel, 2));

    /* return if already absolute URL */
        if( parse_url($rel, PHP_URL_SCHEME) != '' )
        return( $rel );

    /* queries and anchors */
    if( $rel[0]=='#' || $rel[0]=='?' )
        return( $base.$rel );

    /* parse base URL and convert to local variables:
       $scheme, $host, $path */
    $extract = parse_url($base);
    $path = $extract['path'];
    $host = $extract['host'];
    $scheme = $extract['scheme'];

    /* remove non-directory element from path */
    $path = preg_replace( '#/[^/]*$#', '', $path );

    /* destroy path if relative url points to root */
    if( $rel[0] == '/' )
        $path = '';

    /* dirty absolute URL */
    $abs = '';

    /* do we have a user in our URL? */
    if( isset($user) )
    {
        $abs.= $user;

        /* password too? */
        if( isset($pass) )
            $abs.= ':'.$pass;

        $abs.= '@';
    }

    $abs.= $host;

    /* did somebody sneak in a port? */
    if( isset($port) )
        $abs.= ':'.$port;

    $abs.=$path.'/'.$rel;

    /* replace '//' or '/./' or '/foo/../' with '/' */
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for( $n=1; $n>0; $abs=preg_replace( $re, '/', $abs, -1, $n ) ) {}

    /* absolute URL is ready! */
    return( $scheme.'://'.$abs );
}
///////////////Im done copying other people now

if(isset($_POST['findImages']))
{
    require 'Scraper.php';
    $url = $_POST['url'];
    $DOM = new DOMDocument();
    //Browse url
    try {
        $scraper = new Scraper($url);
        @$DOM->loadHTML($scraper->scrape()); //Surpress errors as most html pages are not 100% perfect on the web, but we can still use them
        $url = $scraper->getUrl();
    }
    catch (Exception $e)
    {
        echo "Exception: ", $e->getMessage();
    }

    $count = 0;
    //Print all the images found.
    $images = $DOM->getElementsByTagName('img');
    foreach($images as $image)
    {
        if($image->hasAttribute('src')) {
            $src = @rel2abs($image->getAttribute('src'), $url);
            echo "<img src=\"imageModder.php?u=", urlencode($src), "\" />";
            $count++;
        }
    }

    echo "<br/>",$count, " images found.";
}

?>
</div>
</body>
</html>

