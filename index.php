<?php

/* Title: Sample Test for PreneurLab

 * Task: "use Facebook API to fetch profile picture of any user and store in any server"
 *
 * Target Acquired: 1| Used Facebook API
 *                  2| Fetched Profile Picture of Test Users by Manually
 *                  3| Stored in local Server
 *
 * Submitted By: MD. Rezaul Islam
 *
 * Email: rezaul.cse.mbstu@gmail.com
 *
 * */

//included the API
include_once( "vendor/autoload.php" );

/*
 * ProfilePictureFetcher App
 *
 * Relevant Information goes here
 * */
$appID           = '153521365281379';
$appSecret       = '020d00ad812525192a9e3bcc0f30cccd';
$graph           = 'v2.11';
$accessToken = 'EAACLoHpxamMBAGkJQGJIYPZA1kSex1I1hI14GdWhKkb97Hef39SzpBLZAyhFjgQqiQO9kiOjknmFiRVVFZBICxkTZCZAotbslLDpfWAlF7tg0BOtcZCHMyeKKuHBBm7ouZBivYf6lPT4iZAWAt3bRUzmqDOfKYJQReV9rBJfJr0pjxwj7I1Njo2JERVYILoh0KApMpETNiSANUDO2fjvZAxjrvyAG3cW7XaZCcPG5ZBZAI3kOQZDZD';

// Create our Application instance
$fb = new \Facebook\Facebook( [
	'app_id'                => $appID,
	'app_secret'            => $appSecret,
	'default_graph_version' => $graph,

] );

/* Getting Long Lived Access Token with getOAuth2Client Method
 * Helpful for testing
*/
$oAuth2Client         = $fb->getOAuth2Client();
$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
//var_dump($longLivedAccessToken);

//manually defined longLivedAccessToken for Test Users
$longLivedAccessTokenDave = 'EAACLoHpxamMBANj7Jq2vVC6XReLGr3lf0cw4Ih438cdMOxXbitP4vhqybsnNQStpUXaas4AmkVjnksZA3UDXIysVzxDAGC0UTkhr7vDhkzA0i4ZBHXmdOZBh9HSft7RJFbjYii8KadFcDKmiuOh9cvw9yeobn31dA6ukLZAZB3D9oF5D1ZCtpi';
$longLivedAccessTokenKaren = 'EAACLoHpxamMBAPaAEP9ZAmSytBhSAu610IocwYz8McyAxqUDg4ZCFXmZB1qc0ZBX1v9tqPmIsPEiJ1TLhSrhvx8zFJem40SjsOSPkTWo8265MxAwxqjvnjB0bpQun6PXkegUjkH1C7jt9us1GstlifzZAcCpmvPBjHs8hrmvX9CV4UIq8ZBefC';
$longLivedAccessTokenMargaret = 'EAACLoHpxamMBAPP0UFlfX6DGp699yzRNrDgMYOa1AwOkVCpRdY6ZCNIa7GlsHjgXCF8A50KaLqTsZA8L3O6soiNGxq0jfU4c0BiUitkekARr5xXgqWcjcLmOCnpd3j3C9ZAeu36T7qD828feof28Bxrv7s2f2OrVmHKGfa0usM5X6BrOPpl';

// Setting DefaultAccessToken
// For test just change the $longLivedAccessTokenDave into another
$fb->setDefaultAccessToken( $longLivedAccessTokenDave);

/* make the API call */
try {
	// Returns a `Facebook\FacebookResponse` object

	// GET for User Info
	$responseMe = $fb->get(
		'/me'
	);

	// GET for Profile Picture
	$responsePicture = $fb->get(
		'/me/picture?type=large&redirect=false'
	);


} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

// Refining with getGraphObject for User Info
$me   = $responseMe->getGraphObject();
$id   = $me->getProperty( 'id' );
$user = $me->getProperty( 'name' );
echo '<pre>';
//var_dump( $user );
echo '</pre>';

// Refining with getGraphObject for Profile Picture
$picture     = $responsePicture->getGraphObject();
$pictureUrl  = $picture->getProperty( 'url' );
$pictureName = $id . '.' . 'jpg';
echo '<pre>';
//var_dump( $picture );
echo '</pre>';

//Get the file
$content = file_get_contents( $pictureUrl );

//Store in the filesystem.
$fp = fopen( "img/$pictureName", "w" );
fwrite( $fp, $content );
fclose( $fp );

?>

<!doctype html>
<html lang="en">
<head>
    <title>Profile Picture Fetcher</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="col-md-12" style="height: 25px">

    </div>

    <div class="col-md-12">
        <h3>User Name: </h3><?php echo $user ?>
        <br>
        <h3>Profile Picture:</h3>
        <br>
        <img src="img/<?php echo $pictureName ?>">
    </div>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
        integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
        integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
        crossorigin="anonymous"></script>
</body>
</html>






