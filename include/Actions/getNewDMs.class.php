<?php
/**
 * getNewDMs.class.php - Inserts into DB new received DMs
 *
 * Queries for new DMs and stores it in database for process 
 * them later.
 *
 * @author      Soukron <soukron@gmbros.net>
 * @version     1.0
 * @copyright   Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license     http://www.gnu.org/licenses/gpl.html     GPL License
 */
require_once ("Action.class.php");
class getNewDMs extends Action {
	function doAction() {
		$DMs = $this->bot->getDMs();
		if(count($DMs)) {
			Logger::instance()->log("Adding new DMs in database", 4);
			$db = $this->bot->getDbObject();
			$insert = $db->prepare("INSERT INTO dms(timestamp, data) VALUES (DATETIME('NOW'), ?)");

			foreach ($DMs as $DM)
				$insert->execute(array(json_encode($DM)));
		}
	}
}


