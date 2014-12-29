<?php $this->tplDisplay("inc_header.php", ['menu'=>'news','sPageTitle'=>"News &raquo; Create Article"]); ?>

	<h1>News &raquo; Create Article</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<form id="add-form" method="post" action="/admin/news/add/s/" enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="span8">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Title</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<input type="text" name="title" id="form-title" value="<?= $aPost['title'] ?>" class="span12 validate[required]">
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Content</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<?= html_editor($aPost['content'], "content") ?>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Excerpt</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<textarea name="excerpt" class="span12" style="height:115px;"><?= $aPost['excerpt'] ?></textarea>
								<p class="help-block"><span id="currentCharacters"></span> of <?= $sExcerptCharacters ?> characters</p>
							</div>
						</div>
					</div>
				</div>

				<?php if($sUseCategories == true): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Categories</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<?php if(!empty($aCategories)): ?>
									<select name="categories[]" data-placeholder="Select Categories" class="chzn-select span12" multiple="">
										<?php foreach($aCategories as $aCategory): ?>
											<option value="<?= $aCategory['id'] ?>"<?php if(in_array($aCategory['id'], $aPost['categories'])){ echo ' selected="selected"'; } ?>><?= $aCategory['name'] ?></option>
										<?php endforeach; ?>
	             		</select>

				          <p class="help-block">Hold down ctrl (or cmd) to select multiple categories at once.</p>
								<?php else: ?>
			          	<p>There are currently no categories. Need to <a href="#" title="">add one</a>?</p>
			          <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<div class="span4 aside">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Publish</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group cf">
								<div class="controls">
									<input type="submit" name="submit-type" value="Save Draft" class="btn pull-left">
									<input type="submit" name="submit-type" value="Publish" class="btn btn-primary pull-right">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Publish(ed) On</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<div class="controls timepicker">
									<input type="input" name="publish_on_date" value="<?= $aPost['publish_on_date'] ?>" id="datepicker" class="span12">
									@ <?= html_select_time($aPost['publish_on'], "publish_on_", 15, false, false); ?>

									<p class="help-block">If the date is in the future, it will not show on the site till then.</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Social Sharing</span>
					</div>
					<div class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="post_twitter" value="1"<?php if($aPost['post_twitter'] == 1){ echo ' checked="checked"'; } ?>> <img src="/images/admin/social/twitter.png" width="15px"> Share this post to Twitter.</label>
								</div>

								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="post_facebook" value="1"<?php if($aPost['post_facebook'] == 1){ echo ' checked="checked"'; } ?>> <img src="/images/admin/social/facebook_32.png" width="15px"> Share this post to Facebook.</label>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Tags</span>
					</div>
					<div class="accordion-body in collapse">
						<div class="accordion-inner">
							<div class="controls">
								<textarea name="tags" id="form-tags" style="height:115px;" class="span12"><?= $aPost['tags'] ?></textarea>
								<p class="help-block">Comma separated list of keywords.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

{footer}
<script>
$(function(){
	jQuery('#add-form').validationEngine({ promptPosition: "bottomLeft" });

	$('#datepicker').datepicker({
		dateFormat: 'DD, MM dd, yy',
		changeMonth: true,
		changeYear: true
	});

	$('#currentCharacters').html($('textarea[name=excerpt]').val().length);
	$('textarea[name=excerpt]').keyup(function() {
		if($(this).val().length > <?php echo $sExcerptCharacters; ?>)
			$('#currentCharacters').parent().css('color', '#cc0000');
		else
			$('#currentCharacters').parent().css('color', 'inherit');
		$('#currentCharacters').html($(this).val().length);
	});
});
</script>
{/footer}

<?php $this->tplDisplay("inc_footer.php"); ?>
