<?php
/**
 * getNewMentions.class.php - Inserts new mentions in DB
 *
 * Queries for new mentions and stores it in database for process
 * them later.
 *
 * @author      Soukron <soukron@gmbros.net>
 * @version     1.0
 * @copyright   Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license     http://www.gnu.org/licenses/gpl.html     GPL License
 */
require_once ("Action.class.php");
class getNewMentions extends Action {
	function doAction() {
		$mentions = $this->bot->getMentions();
		if(count($mentions)) {
			Logger::instance()->log("Adding mentions to database", 4);
			$db = $this->bot->getDbObject();
			$insert = $db->prepare("INSERT INTO mentions(timestamp, data) VALUES (DATETIME('NOW'), ?)");

			foreach ($mentions as $mention)
				$insert->execute(array(json_encode($mention)));
		}
	}
}


