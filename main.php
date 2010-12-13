<?php
/**
 * Main controller
 *
 * @copyright  	Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license    	http://www.gnu.org/licenses/gpl.html     GPL License
 *
 */

set_time_limit(600);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

require_once ("include/config.php");
require_once ("include/SigueMiLink/SigueMiLink.class.php");
require_once ("include/Log/Log.class.php");
require_once ("include/BotActions.class.php");

$Bot = new BotActions($bot_name, $oauth_consumer_key, $oauth_consumer_secret, $db_str, $actions);

$Bot->doHeartBeat();

?>
