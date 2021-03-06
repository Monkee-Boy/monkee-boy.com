<?php $aContent = getContent(null, 'contact');
if(!empty($aContent)) {
	$sTitle = $aContent['title'];
	$sSubtitle = $aContent['subtitle'];
} else {
	$sTitle = "Find Us";
	$sSubtitle = "Beware of nerf wars";
}

$this->tplDisplay("inc_header.php", ['menu'=>'contact', 'page_title'=>$sTitle, 'seo_title'=>$aContent['seo_title'], 'seo_description'=>$aContent['seo_description'], 'seo_keywords'=>$aContent['seo_keywords']]); ?>

<div class="row">
	<div class="page-title">
		<h1><?= $sTitle ?></h1> <?php echo $menu; ?>
		<p class="subtitle"><?= $sSubtitle ?></p>
	</div>
</div>

<?php $this->tplDisplay('inc_subnav.php', array('menu' => 'contact', 'nav' => 'contact')); ?>

<div class="row">
	<div class="full" data-text-align="center">
		<div id="contact-map"></div>

		<h2>9390 Research Blvd. Bldg II, Suite 425, Austin, TX 78759</h2>

		<p><a href="https://www.google.com/maps/dir//9390+Research+Blvd,+Austin,+TX+78759/@30.383167,-97.7785571,13z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x8644cb78dd511803:0xd64e79b84be26668!2m2!1d-97.7435376!2d30.3830976" target="_blank">Get Directions to Monkee-Boy World Headquarters</a></p>
	</div>
</div>

<div class="phone-directory accordion-section">
	<div class="row">
		<div class="full" data-text-align="center">
			<span class="subtitle">give us a ring</span>
			<h2>(512) 335-2221</h2>
		</div>
	</div>
</div>

<script src="https://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>

<?php $this->tplDisplay("inc_footer.php"); ?>
