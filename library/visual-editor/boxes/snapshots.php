<?php
upfront_register_visual_editor_box('UpFrontSnapshotsBox');
class UpFrontSnapshotsBox extends UpFrontVisualEditorBoxAPI {


	/**
	 *	Slug/ID of panel.  Will be used for HTML IDs and whatnot.
	 **/
	protected $id = 'snapshots';


	/**
	 * Name of panel.  This will be shown in the title.
	 **/
	protected $title = 'Snapshots';

	protected $description;


	/**
	 * Which mode to put the panel on.
	 **/
	protected $mode = 'all';

	protected $center = true;

	protected $width = 400;

	protected $height = 500;

	protected $min_width = 350;

	protected $min_height = 200;

	protected $closable = true;

	protected $draggable = true;

	protected $resizable = false;

	function __construct(){
		$this->description = __('Stelle Deine Arbeit mit Snapshots wieder her.', 'upfront');
	}


	public function content() {

		echo '
		<span class="button button-blue" data-bind="click: saveSnapshot">Snapshot speichern</span>
		<span class="spinner"></span>

		<ul id="snapshots-list" data-bind="foreach: snapshots">
			<li data-bind="attr: {id: \'snapshot-\' + id}">
				<span class="snapshot-timestamp" data-bind="text: $parent.formatSnapshotDatetime(timestamp)"></span>
				<span class="snapshot-delete" data-bind="click: $parent.deleteSnapshot" title="Snapshot löschen">Delete</span>

				<span class="button button-small" data-bind="click: $parent.rollbackToSnapshot">Rollback</span>

				<p class="snapshot-comments" data-bind="text:comments, visible: comments"></p>
			</li>
		</ul>
		';

	}


}