<?php
	require ("config.php");
	require_once ("Twitter/TwitterOAuth.class.php");

	$db = new PDO($db_str);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
                $tokens = array();
                foreach ($db->query("SELECT `token_name`, `token_value` FROM `tokens`") as $row)
                        $tokens[$row["token_name"]] = $row["token_value"];
        }
        catch(PDOException $e) {
                echo $e->getMessage();
        }

	$action = $argv[1];
	$pin = @$argv[2];

	if ($action == "register")
	{
		$oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret);
		$request = $oauth->getRequestToken();

		$request_token = $request["oauth_token"];
		$request_token_secret = $request["oauth_token_secret"];

		// At this stage you should store the two request tokens somewhere.
		// Database or file, whatever. Just make sure it's safe and nobody can read it!
		// I'll dump mine into files using file_put_content:

		$tokens["request_token"] = $request_token;
		$tokens["request_token_secret"] = $request_token_secret;

		$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('request_token', '{$tokens["request_token"]}');");
		$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('request_token_secret', '{$tokens["request_token_secret"]}');");

		// Generate a request link and output it
		$request_link = $oauth->getAuthorizeURL($request);
		echo "Request here: $request_link \n";
		die();
	}
	elseif ($action == "clear")
	{
		echo "Tokens cleared";
		die();
	}
	elseif ($action == "validate")
	{
		// This is the validation part. At this point you should read the stored request
		// tokens. You'll need them to get your access tokens!
		// Mine are located in two files:

		$request_token = $tokens["request_token"];
		$request_token_secret = $tokens["request_token_secret"];

		// Initiate a new TwitterOAuth object. This time we provide them with more details:
		// The request token and the request token secret
		$oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret, $request_token, $request_token_secret);

		// Ask Twitter for an access token (and an access token secret)
		$request = $oauth->getAccessToken($pin);

		// There we go
		$access_token = $request['oauth_token'];
		$access_token_secret = $request['oauth_token_secret'];

		$tokens["access_token"] = $access_token;
		$tokens["access_token_secret"] = $access_token_secret;

		// Now store the two tokens into another file (or database or whatever):
		$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('access_token', '{$tokens["access_token"]}');");
		$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('access_token_secret', '{$tokens["access_token_secret"]}');");


		// Great! Now we've got the access tokens stored.
		// Let's verify credentials and output the username.
		// Note that this time we're passing TwitterOAuth the access tokens.
		$oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
			$access_token, $access_token_secret);

		// Send an API request to verify credentials
		$credentials = $oauth->get("account/verify_credentials");

		// And finaly output some text
		echo "Access token saved! Authorized as @" . $credentials->screen_name . "\n";
		die();
	}
	elseif ($action == "initdb")
	{
		$access_token = $tokens["access_token"];
		$access_token_secret = $tokens["access_token_secret"];

		$oauth = new TwitterOAuth($oauth_consumer_key, $oauth_consumer_secret,
			$access_token, $access_token_secret);

		echo "Updating mentions_since_id token.\n";
		$mentions = $oauth->get("statuses/mentions" , array("count" => 1));
		if (count($mentions)) {
			$last_id = $mentions[0]->id;
			$tokens["mentions_since_id"] = (string)$last_id;
			$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('mentions_since_id', '{$tokens["mentions_since_id"]}');");
		}

		echo "Updating dm_since_id token.\n";
		$dms = $oauth->get("direct_messages" , array("count" => 1));
		if (count($dms)) {
			$last_id = $dms[0]->id;
			$tokens["dm_since_id"] = (string)$last_id;
			$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('dm_since_id', '{$tokens["dm_since_id"]}');");
		}

		echo "Updating last_heartbeat token.\n";
		$db->query("REPLACE INTO `tokens` (`token_name`, `token_value`) VALUES ('last_heartbeat', '".time()."');");

	}
