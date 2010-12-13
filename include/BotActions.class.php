<?php
/**
 * BotActions.class.php - Extends Bot to add hearbeat and actions
 *
 * This class implements actions capabilities to Bot class
 *
 * @author 	Soukron <soukron@gmbros.net>
 * @version 	1.0
 * @copyright  	Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license    	http://www.gnu.org/licenses/gpl.html     GPL License
 */
require_once ("Bot.class.php");
class BotActions extends Bot {
	protected $actions = array();

	function __construct($name, $consumer_key, $consumer_secret, $db_str, $actions) {
		$this->actions = $actions;
		parent::__construct($name, $consumer_key, $consumer_secret, $db_str);
	}

	/**
	* doHeartBeat - Makes the "magic" inside the bot
	*/
	function doHeartBeat() {
		$heartbeat = time();
		Logger::instance()->log("TIMESTAMP ($heartbeat):");

		foreach ($this->actions as $action) {
			//if (file_exists("Actions/".$action.".class.php")) {
				require_once("Actions/".$action.".class.php");
				Logger::instance()->log("Calling $action", 2);
				$action = new $action($this, $heartbeat);
				$action->doAction();
			//}
		}

		// Update token in database
		$this->setToken("last_heartbeat", $heartbeat);
	}
}
?>
