<?php
/**
 * Action.class.php 
 *
 * @author 	Soukron <soukron@gmbros.net>
 * @version 	1.0
 * @copyright  	Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license    	http://www.gnu.org/licenses/gpl.html     GPL License
 */
class Action {
	protected $bot;
	protected $heartbeat;

	public function __construct($bot, $hearbeat) {
		$this->bot = $bot;
		$this->heartbeat = $hearbeat;
	}

	public function doAction() {
		Logger::instance()->log("Executed action", 2);
	}
}
?>
