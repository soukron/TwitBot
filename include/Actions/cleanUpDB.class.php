<?php
/**
 * cleanUpDB.class.php - Empty mentions and dms tables
 *
 * Cleans any mentions and dms stored in database
 *
 * @author      Soukron <soukron@gmbros.net>
 * @version     1.0
 * @copyright   Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license     http://www.gnu.org/licenses/gpl.html     GPL License
 */
require_once ("Action.class.php");
class cleanUpDB extends Action {
	function doAction() {
		Logger::instance()->log("Cleaning database tables", 4);
                $db = $this->bot->getDbObject();
                $db->query("DELETE FROM mentions;");
		$db->query("DELETE FROM dms;");
	}
}


