<div id="export-template" style="display: none;">
	<h3><?php _e('Vorlage exportieren', 'upfront'); ?></h3>
	<p><?php _e('Fülle die folgenden Informationen aus, um alle Designeinstellungen, Layouts und Blöcke als UpFront-Vorlage zu exportieren', 'upfront'); ?></p>

	<form id="export-template-form">
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row"><label for="template-export-name"><?php _e('Vorlagenname', 'upfront'); ?></label></th>
				<td><input id="template-export-name" type="text" name="skin-export-info[name]" class="regular-text" /></td>
			</tr>

			<?php
			$current_user = wp_get_current_user();
			?>

			<tr valign="top">
				<th scope="row"><label for="template-export-author"><?php _e('Vorlagenautor', 'upfront'); ?></label></th>
				<td><input id="template-export-author" type="text" name="skin-export-info[author]" class="regular-text" value="<?php echo $current_user->display_name; ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-version"><?php _e('Vorlagenversion', 'upfront'); ?></label></th>
				<td><input id="template-export-version" type="text" name="skin-export-info[version]" placeholder="e.g. 1.0" class="medium-text" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="template-export-image"><?php _e('Vorlagenbild', 'upfront'); ?></label></th>
				<td>
					<button id="template-export-image-button" class="button-secondary">
						<span class="wp-media-buttons-icon"></span>
						<?php _e('Bild auswählen', 'upfront'); ?>
					</button>
					<input id="template-export-image" type="hidden" name="skin-export-info[image-url]" class="medium-text" />
					<img src="" id="template-export-image-preview" style="display: none;" />
				</td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="export-template-submit" class="button button-primary" value="<?php _e('Vorlage exportieren', 'upfront'); ?>">
		</p>
	</form>
</div>

<h2><?php _e('UpFront-Vorlagen', 'upfront'); ?>
	<a href="#" class="add-new-h2" id="install-template"><?php _e('Vorlage installieren', 'upfront'); ?></a>
	<a href="#TB_inline?width=500&height=600&inlineId=export-template" class="add-new-h2 thickbox" id="export-template"><?php _e('Aktuelle Vorlage exportieren', 'upfront'); ?></a>
</h2>

<div id="upfront-admin-notifications"></div>

<div class="theme-browser" id="upfront-templates-browser">
	<div class="themes upfront-templates">
		<!-- ko foreach: templates -->
			<div class="theme upfront-template" tabindex="0" aria-describedby="upfront-action upfront-name" data-bind="attr: { 'data-template-id': id }, css: { 'active': $parent.active().id == id, 'missing-image': !$data['image-url'], 'template-installing': (typeof $data['installing'] != 'undefined' && $data['installing']) }">

				<div class="theme-screenshot">
					<span class="template-loading-indicator" data-bind="visible: (typeof $data['installing'] != 'undefined' && $data['installing'])"></span>
					<img src="" alt="" data-bind="visible: $data['image-url'], attr: { 'src': $data['image-url'] }" />
				</div>

				<div class="theme-author" data-bind="text: 'Von ' + author, visible: author"></div>

				<h3 class="theme-name" id="upfront-name"><span data-bind="visible: $parent.active().id == id"><?php _e('Aktiv: ', 'upfront'); ?></span><!-- ko text: name --><!-- /ko --> <!-- ko text:version --><!-- /ko --></h3>

				<div class="theme-actions" data-bind="visible: (typeof $data['installing'] == 'undefined' || !$data['installing'])">
					<a href="#" class="button button-secondary delete-template" data-bind="click: $parent.deleteSkin, visible: (id != $parent.active().id && id != 'base')"><?php _e('Löschen', 'upfront'); ?></a>
					<a class="button button-primary" href="#" data-bind="click: $parent.activateSkin, visible: id != $parent.active().id"><?php _e('Aktivieren', 'upfront'); ?></a>
				</div>

			</div>
		<!-- /ko -->

		<div class="theme add-new-theme" id="add-blank-template">
			<a href="#">
				<div class="theme-screenshot"><span></span></div>
				<h3 class="theme-name"><?php _e('Leere Vorlage hinzufügen', 'upfront'); ?></h3>
			</a>
		</div>
	</div>
	<br class="clear">
</div>

<form id="upload-skin">
	<input type="file" />
</form>