<?php $this->tplDisplay("inc_header.php", ['menu'=>'content','subMenu'=>'Pages','sPageTitle'=>"Content Pages &raquo; Create Page"]); ?>

	<h1>Content Pages &raquo; Create Page</h1>
	<?php $this->tplDisplay('inc_alerts.php'); ?>

	<form id="add-form" method="post" action="/admin/content/add/s/">
		<div class="row-fluid">
			<div class="span8">
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Page Title</span>
					</div>
					<div id="pagecontent" class="accordion-body">
						<div class="accordion-inner">
							<div class="controls">
								<input type="text" name="title" id="form-title" value="<?= $aPage['title'] ?>" class="span12 validate[required]">
								<p class="help-block permalink hide"><strong>Permalink</strong>: http://<?= $_SERVER['SERVER_NAME'] ?>/<span></span></p>

								<hr>

								<label for="form-subtitle">Subtitle</label>
								<input type="text" name="subtitle" id="form-subtitle" value="<?= $aPage['subtitle'] ?>" class="span12 validate[required]">
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
								<?= html_editor($aPage['content'], 'content') ?>
							</div>
						</div>
					</div>
				</div>

				<input type="submit" value="Create Page" class="btn btn-primary">
				<a href="/admin/content/" title="Cancel" class="btn">Cancel</a>
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

							<?php if($sSuperAdmin): ?>
							<div class="control-group">
								<div class="controls">
									<label class="checkbox"><input type="checkbox" name="permanent" id="form-permanent" value="1"<?php if($aPage['permanent'] == 1){ echo ' checked="checked"'; } ?>>Permanent</label>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if($sSuperAdmin): ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Page Options</span>
					</div>
					<div id="pageoptions" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-tag">Tag</label>
								<div class="controls">
									<input type="text" name="tag" id="form-tag" value="<?= clean_html($aPage['tag']) ?>" class="span12">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-template">Page Template</label>
								<div class="controls">
									<select name="template" id="form-template">
										<option value="">Default Template</option>
										<?php foreach($aTemplates as $aTemplate): ?>
											<option value="<?= $aTemplate['file'] ?>"<?php if($aPage['template'] == $aTemplate['file']){ echo ' selected="selected"'; } ?>><?= $aTemplate['Name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div> <!-- /.control-group -->

							<div class="control-group">
								<label class="control-label" for="form-parent">Parent</label>
								<div class="controls">
									<select name="parentid" id="form-parent">
										<option value="0">No Parent</option>
										<?php foreach($aPages as $aParentPage): ?>
											<option value="<?= $aParentPage['id'] ?>"<?php if($aPage['parentid'] == $aParentPage['id']){ echo ' selected="selected"'; } ?>><?= $aParentPage['title']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div> <!-- /.control-group -->
						</div>
					</div>
				</div>
				<?php endif; ?>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">SEO</span>
					</div>
					<div id="pageseo" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-tag">Title</label>
								<div class="controls">
									<input type="text" name="seo_title" id="form-tags" value="<?= $aPage['seo_title'] ?>" class="span12">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-tag">Description</label>
								<div class="controls">
									<textarea name="seo_description" id="form-tags" style="height:95px;" class="span12"><?= $aPage['seo_description'] ?></textarea>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-tag">Keywords</label>
								<div class="controls">
									<textarea name="seo_keywords" id="form-tags" style="height:95px;" class="span12"><?= $aPage['seo_keywords'] ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">CTA</span>
					</div>
					<div id="pageseo" class="accordion-body">
						<div class="accordion-inner">
							<div class="control-group">
								<label class="control-label" for="form-tag">Line 1</label>
								<div class="controls">
									<input type="text" name="cta_line1" id="form-tags" value="<?= $aPage['cta_line1'] ?>" class="span12">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-tag">Line 2</label>
								<div class="controls">
									<input type="text" name="cta_line2" id="form-tags" value="<?= $aPage['cta_line2'] ?>" class="span12">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="form-tag">Button</label>
								<div class="controls">
									<input type="text" name="cta_button" id="form-tags" value="<?= $aPage['cta_button'] ?>" class="span12">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-group">
					<div class="accordion-heading">
						<span class="accordion-toggle">Tags</span>
					</div>
					<div id="pagetags" class="accordion-body in collapse">
						<div class="accordion-inner">
							<div class="controls">
								<textarea name="tags" id="form-tags" style="height:115px;" class="span12"><?= $aPage['tags'] ?></textarea>
								<p class="help-block">Comma separated list of keywords. Tags are used both for visitors using the site's built-in search and meta keywords which are indexed by search engines like Google.</p>
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

	$('input[name="title"]').focusout(function() {
		if($(this).val()) {
			str = $(this).val().replace(/[^a-z0-9]+/gi, '-').replace(/^-*|-*$/g, '').toLowerCase().substr(0, 100);
			$('.permalink span').text(str).parent().show();
			{if $sSuperAdmin == true}$('input[name="tag"]').val(str);{/if}
		}
	});

	$('input[name="tag"]').focusout(function() {
		if($(this).val()) {
			str = $(this).val().replace(/[^a-z0-9]+/gi, '-').replace(/^-*|-*$/g, '').toLowerCase().substr(0, 100);
			$('.permalink span').text(str).parent().show();
		}
	});
});
</script>
{/footer}
<?php $this->tplDisplay("inc_footer.php"); ?>
