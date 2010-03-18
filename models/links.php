<?php
class links_model extends appModel
{
	public $perPage = 5;
	
	function getLinks($sCategory)
	{
		$sWhere = " WHERE `links`.`active` = 1";
		if(!empty($_GET["category"]))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($_GET["category"], "integer");
		
		// Get all links for paging
		$aLinks = $this->dbResults(
			"SELECT `links`.* FROM `links` AS `links`"
				." INNER JOIN `links_categories_assign` AS `links_assign` ON `links`.`id` = `links_assign`.`linkid`"
				." INNER JOIN `links_categories` AS `categories` ON `links_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `links`.`id`"
			,"all"
		);
	
		foreach($aLinks as $x => $aLink)
		{
			$aLinkCategories = $this->dbResults(
				"SELECT `name` FROM `links_categories` AS `categories`"
					." INNER JOIN `links_categories_assign` AS `links_assign` ON `links_assign`.`categoryid` = `categories`.`id`"
					." WHERE `links_assign`.`linkid` = ".$aLink["id"]
				,"col"
			);
		
			$aLinks[$x]["categories"] = implode(", ", $aLinkCategories);
		}
		
		return $aLinks;
	}
	function getLink($sId)
	{
		$aLink = $this->dbResults(
			"SELECT * FROM `links`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"row"
		);
		
		return $aLink;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `links_categories`"
				." ORDER BY `name`"
			,"all"
		);
		
		return $aCategories;
	}
}