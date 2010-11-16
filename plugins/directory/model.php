<?php
class directory_model extends appModel {
	public $useImage = true;
	public $imageMinWidth = 140;
	public $imageMinHeight = 87;
	public $imageFolder = "/uploads/directory/";
	public $useCategories = true;
	public $perPage = 5;
	public $sort = "name-asc"; // manual, name, state, created, updated, random - asc, desc
	public $aStates = array(''=>"",
							'AL'=>"Alabama",  
							'AK'=>"Alaska",  
							'AZ'=>"Arizona",  
							'AR'=>"Arkansas",  
							'CA'=>"California",  
							'CO'=>"Colorado",  
							'CT'=>"Connecticut",  
							'DE'=>"Delaware",  
							'DC'=>"District Of Columbia",  
							'FL'=>"Florida",  
							'GA'=>"Georgia",  
							'HI'=>"Hawaii",  
							'ID'=>"Idaho",  
							'IL'=>"Illinois",  
							'IN'=>"Indiana",  
							'IA'=>"Iowa",  
							'KS'=>"Kansas",  
							'KY'=>"Kentucky",  
							'LA'=>"Louisiana",  
							'ME'=>"Maine",  
							'MD'=>"Maryland",  
							'MA'=>"Massachusetts",  
							'MI'=>"Michigan",  
							'MN'=>"Minnesota",  
							'MS'=>"Mississippi",  
							'MO'=>"Missouri",  
							'MT'=>"Montana",
							'NE'=>"Nebraska",
							'NV'=>"Nevada",
							'NH'=>"New Hampshire",
							'NJ'=>"New Jersey",
							'NM'=>"New Mexico",
							'NY'=>"New York",
							'NC'=>"North Carolina",
							'ND'=>"North Dakota",
							'OH'=>"Ohio",  
							'OK'=>"Oklahoma",  
							'OR'=>"Oregon",  
							'PA'=>"Pennsylvania",  
							'RI'=>"Rhode Island",  
							'SC'=>"South Carolina",  
							'SD'=>"South Dakota",
							'TN'=>"Tennessee",  
							'TX'=>"Texas",  
							'UT'=>"Utah",  
							'VT'=>"Vermont",  
							'VA'=>"Virginia",  
							'WA'=>"Washington",  
							'WV'=>"West Virginia",  
							'WI'=>"Wisconsin",  
							'WY'=>"Wyoming");
	
	function getListings($sCategory, $sAll = false) {
		$aWhere = array();
		$sJoin = "";
		
		// Filter those that are only active, unless told otherwise
		if($sAll == false) {
			$aWhere[] = "`directory`.`active` = 1";
		}
		
		// Filter by category if given
		if(!empty($sCategory)) {
			$aWhere[] = "`categories`.`id` = ".$this->dbQuote($sCategory, "integer");
			$sJoin .= " LEFT JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `directory`.`id` = `directory_assign`.`listingid`";
			$sJoin .= " LEFT JOIN `{dbPrefix}directory_categories` AS `categories` ON `directory_assign`.`categoryid` = `categories`.`id`";
		}
		
		// Combine filters if atleast one was added
		if(!empty($aWhere)) {
			$sWhere = " WHERE ".implode(" AND ", $aWhere);
		}
		
		// Check if sort direction is set, and clean it up for SQL use
		$sSortDirection = array_pop(explode("-", $this->sort));
		if(empty($sSortDirection) || !in_array(strtolower($sSortDirection), array("asc", "desc"))) {
			$sSortDirection = "ASC";
		} else {
			$sSortDirection = strtoupper($sSortDirection);
		}
			
		// Choose sort method based on model setting
		switch(array_shift(explode("-", $this->sort))) {
			case "manual":
				$sOrderBy = " ORDER BY `sort_order` ".$sSortDirection;
				break;
			case "created":
				$sOrderBy = " ORDER BY `created_datetime` ".$sSortDirection;
				break;
			case "updated":
				$sOrderBy = " ORDER BY `updated_datetime` ".$sSortDirection;
				break;
			case "random":
				$sOrderBy = " ORDER BY RAND()";
				break;
			case "state":
				$sOrderBy = " ORDER BY `state` ".$sSortDirection;
				break;
			// Default to sort by name
			default:
				$sOrderBy = " ORDER BY `name` ".$sSortDirection;
		}
		
		$aListings = $this->dbQuery(
			"SELECT `directory`.* FROM `{dbPrefix}directory` AS `directory`"
				.$sJoin
				.$sWhere
				." GROUP BY `directory`.`id`"
				.$sOrderBy
			,"all"
		);
	
		foreach($aListings as $x => &$aListing) {
			$aListing = $this->_getListingInfo($aListing);
		}
		
		return $aListings;
	}
	function getListing($sId, $sTag = null, $sAll = false) {
		if(!empty($sId))
			$sWhere = " WHERE `directory`.`id` = ".$this->dbQuote($sId, "integer");
		else
			$sWhere = " WHERE `directory`.`tag` = ".$this->dbQuote($sTag, "text");
			
		if($sAll == false)
			$sWhere .= " AND `directory`.`active` = 1";
		
		$aListing = $this->dbQuery(
			"SELECT `directory`.* FROM `{dbPrefix}directory` AS `directory`"
				.$sWhere
				." LIMIT 1"
			,"row"
		);
	
		if(!empty($aListing)) {
			$aListing = $this->_getListingInfo($aListing);
		}
		
		return $aListing;
	}
	private function _getListingInfo($aListing) {
		$aListing["name"] = htmlspecialchars(stripslashes($aListing["name"]));
		$aListing["address1"] = htmlspecialchars(stripslashes($aListing["address1"]));
		$aListing["address2"] = htmlspecialchars(stripslashes($aListing["address2"]));
		$aListing["city"] = htmlspecialchars(stripslashes($aListing["city"]));
		$aListing["stateFull"] = array_pop(explode(",", htmlspecialchars(stripslashes($aListing["state"]))));
		$aListing["state"] = array_shift(explode(",", htmlspecialchars(stripslashes($aListing["state"]))));
		$aListing["zip"] = htmlspecialchars(stripslashes($aListing["zip"]));
		$aListing["phone"] = htmlspecialchars(stripslashes($aListing["phone"]));
		$aListing["fax"] = htmlspecialchars(stripslashes($aListing["fax"]));
		$aListing["email"] = htmlspecialchars(stripslashes($aListing["email"]));
		$aListing["website"] = htmlspecialchars(stripslashes($aListing["website"]));
		
		$aListing["categories"] = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}directory_categories` AS `categories`"
				." INNER JOIN `{dbPrefix}directory_categories_assign` AS `directory_assign` ON `directory_assign`.`categoryid` = `categories`.`id`"
				." WHERE `directory_assign`.`listingid` = ".$aListing["id"]
			,"all"
		);
		
		foreach($aListing["categories"] as &$aCategory) {
			$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		}
		
		if(file_exists($this->settings->rootPublic.substr($this->imageFolder, 1).$aListing["id"].".jpg")
		 && $aListing["photo_x2"] > 0
		 && $this->useImage == true) {
			$aListing["image"] = 1;
		} else {
			$aListing["image"] = 0;
		}
			
		return $aListing;
	}
	function getURL($sID) {
		$aListing = $this->getListing($sID);
		
		$sURL = "/directory/";
		
		return $sURL;
	}
	function getCategories($sEmpty = true) {		
		if($sEmpty == true) {		
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories`"
					." ORDER BY `name`"
				,"all"
			);
		
			foreach($aCategories as &$aCategory) {
				$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
			}
		} else {
			$aCategories = $this->dbQuery(
				"SELECT * FROM `{dbPrefix}directory_categories_assign`"
					." GROUP BY `categoryid`"
				,"all"
			);
			
			foreach($aCategories as $x => $aCategory) {
				$aCategories[$x] = $this->getCategory($aCategory["categoryid"]);
			}
		}
		
		return $aCategories;
	}
	function getCategory($sId = null, $sName = null) {
		if(!empty($sId)) {
			$sWhere = " WHERE `id` = ".$this->dbQuote($sId, "integer");
		} elseif(!empty($sName)) {
			$sWhere = " WHERE `name` LIKE ".$this->dbQuote($sName, "text");
		} else {
			return false;
		}
		
		$aCategory = $this->dbQuery(
			"SELECT * FROM `{dbPrefix}directory_categories`"
				.$sWhere
			,"row"
		);
		
		$aCategory["name"] = htmlspecialchars(stripslashes($aCategory["name"]));
		
		return $aCategory;
	}
	function getImage($sId) {
		$aListing = $this->getListing($sId, true);
		
		$sFile = $this->settings->rootPublic.substr($this->imageFolder, 1).$sId.".jpg";
		
		$aImage = array(
			"file" => $sFile
			,"info" => $aListing
		);
		
		return $aImage;
	}
}