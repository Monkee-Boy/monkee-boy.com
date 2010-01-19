<?php
class galleries_model extends appModel
{
	public $perPage = 5;
	
	function getGalleries($sCategory)
	{
		$sWhere = " WHERE `galleries`.`id` > 0";
		if(!empty($sCategory))
			$sWhere .= " AND `categories`.`id` = ".$this->dbQuote($sCategory, "integer");
		
		// Get all gallerys for paging
		$aGalleries = $this->dbResults(
			"SELECT `galleries`.* FROM `galleries` AS `galleries`"
				." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries`.`id` = `galleries_assign`.`galleryid`"
				." INNER JOIN `galleries_categories` AS `categories` ON `galleries_assign`.`categoryid` = `categories`.`id`"
				.$sWhere
				." GROUP BY `galleries`.`id`"
			,"model->galleries->getGalleries"
			,"all"
		);
	
		foreach($aGalleries as $x => $aGallery)
		{
			$aGalleries[$x]["photo"] = $this->dbResults(
				"SELECT `photo` FROM `galleries_photos`"
					." WHERE `galleryid` = ".$aGallery["id"]
					." AND `gallery_default` = 1"
				,"model->galleries->getGalleries->gallery_default_photo"
				,"one"
			);
			
			$aGalleryCategories = $this->dbResults(
				"SELECT `name` FROM `galleries_categories` AS `categories`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `categories`.`id`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
				,"model->galleries->getGalleries->gallery_categories"
				,"col"
			);
			
			$aGalleries[$x]["categories"] = implode(", ", $aGalleryCategories);
		}
		
		return $aGalleries;
	}
	function getGallery($sId)
	{
		$aGallery = $this->dbResults(
			"SELECT * FROM `galleries`"
				." WHERE `id` = ".$this->dbQuote($sId, "integer")
			,"model->galleries->getGallery"
			,"row"
		);
		
		if(!empty($aGallery))
		{
			$aCategories = $this->dbResults(
				"SELECT `name` FROM `galleries_categories` AS `category`"
					." INNER JOIN `galleries_categories_assign` AS `galleries_assign` ON `galleries_assign`.`categoryid` = `category`.`id`"
					." WHERE `galleries_assign`.`galleryid` = ".$aGallery["id"]
				,"model->galleries->getGallery->categories"
				,"col"
			);

			$aGallery["categories"] = implode(", ", $aCategories);
		}
		
		return $aGallery;
	}
	function getPhotos($sId)
	{
		$aPhotos = $this->dbResults(
			"SELECT * FROM `galleries_photos`"
				." WHERE `galleryid` = ".$this->dbQuote($sId, "integer")
			,"model->galleries->getPhotos"
			,"all"
		);
		
		return $aPhotos;
	}
	function getCategories()
	{
		$aCategories = $this->dbResults(
			"SELECT * FROM `galleries_categories`"
				." ORDER BY `name`"
			,"model->galleries->getCategories"
			,"all"
		);
		
		return $aCategories;
	}
}