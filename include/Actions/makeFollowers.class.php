<?php
/**
 * MakeFollowers.class.php - Implements followback 
 *
 * This action implements "followback" to anyone
 *
 * @author 	Soukron <soukron@gmbros.net>
 * @version 	1.0
 * @copyright  	Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license    	http://www.gnu.org/licenses/gpl.html     GPL License
 */
require_once ("Action.class.php");
class MakeFollowers extends Action {
	/**
	* getNewFollowers - Obtiene new followers from the list
	*
	* This function get *only* new followers from a given reference
	* user id.
	*
	* @param	int lastFollowerId	Reference ID
	*/
	function getNewFollowers($lastFollowerId = NULL) {
		$ret = array();
		$followers = $this->bot->getFollowers();

		foreach($followers as $follower)
			if ($follower->id != $lastFollowerId)
				$ret[] = $follower;
			else
			    break;

		return $ret;
	}

	function doAction() {
		// Use a token stored in database with last known follower
		$lastFollowerId = $this->bot->getToken("last_follower_id");
		$newFollowers = $this->getNewFollowers($lastFollowerId);

		foreach ($newFollowers as $follower) {
			$this->bot->doFollow($follower->screen_name);
			Logger::instance()->log("Followback to ".$follower->screen_name, 4);
		}

		// Update token in database
		if (count($newFollowers)) $this->bot->setToken("last_follower_id",$newFollowers[0]->id);
	}
}
?>
