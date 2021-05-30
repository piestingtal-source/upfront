<?php

upfront_register_admin_meta_box('UpFrontMetaBoxTemplate');
class UpFrontMetaBoxTemplate extends UpFrontAdminMetaBoxAPI {

	protected $id;	
	protected $name;				
	protected $context;			
	protected $inputs;

	public function __construct(){

		$this->id = 'template';
		$this->name = __( 'Gemeinsames Layout', 'upfront');
		$this->context = 'side';
		$this->inputs = array(
			'template' => array(
				'id' => 'template',
				'type' => 'select',
				'options' => array(),
				'description' => __('Weise diesem Eintrag ein gemeinsames Layout zu. Freigegebene Layouts können im UpFront Visual Editor hinzugefügt und geändert werden.', 'upfront'),
				'blank-option' => __('&ndash; Verwende kein freigegebenes Layout &ndash;', 'upfront')
			)
		);

	}

	protected function modify_arguments($post = false) {

		$this->inputs['template']['options'] = UpFrontLayout::get_templates();

		$post_type = get_post_type_object( $post->post_type );

		$this->inputs['template']['description'] = str_replace('entry', strtolower($post_type->labels->singular_name), $this->inputs['template']['description']);

	}

}


upfront_register_admin_meta_box('UpFrontMetaBoxTitleControl');
class UpFrontMetaBoxTitleControl extends UpFrontAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $context;
	protected $inputs;

	public function __construct(){

		$this->id = 'alternate-title';	
		$this->name = 'Titelkontrolle';				
		$this->context = 'side';			
		$this->inputs = array(
			'hide-title' => array(
				'id' => 'hide-title',
				'name' => __('Titel ausblenden', 'upfront'),
				'type' => 'select',
				'blank-option' => __('&ndash; Titel nicht ausblenden &ndash;', 'upfront'),
				'options' => array(
					'singular' => __('In Einzelansicht ausblenden', 'upfront'),
					'list' => __('In Index und Archiv ausblenden', 'upfront'),
					'both' => __('In Einzelansicht, Index und Archiv ausblenden', 'upfront')
				),
				'description' => __('Wähle aus, ob Du den Titel für diesen Eintrag ausblenden möchtest oder nicht. Dies kann nützlich sein, wenn Du die Formatierung in diesem Eintrag erweitert hast.', 'upfront'),
			),

			'alternate-title' => array(
				'id' => 'alternate-title',
				'name' => __('Alternativer Titel', 'upfront'),
				'type' => 'text',
				'description' => __('Mit dem alternativen Seitentitel kannst Du den Titel überschreiben, der im Inhaltsblock der Seite angezeigt wird. Auf diese Weise kannst Du im Navigationsmenü einen kürzeren Seitentitel und <code>&lt;title&gt;</code> haben, im eigentlichen Seiteninhalt jedoch einen längeren und aussagekräftigeren Titel.', 'upfront')
			)
		);
	}


}


upfront_register_admin_meta_box('UpFrontMetaBoxDisplay');
class UpFrontMetaBoxDisplay extends UpFrontAdminMetaBoxAPI {

	protected $id;	
	protected $name;
	protected $inputs;

	public function __construct(){

		$this->id = 'display';
		$this->name = __('Anzeige', 'upfront');
		$this->inputs = array(
			'css-class' => array(
				'id' => 'css-class',
				'name' => __('Benutzerdefinierte CSS-Klasse(n)', 'upfront'),
				'type' => 'text',
				'description' => __('Wenn Du mit <a href="http://www.w3schools.com/css/" target="_blank">CSS</a> vertraut bist und diesen Eintrag durch Ausrichtung auf eine bestimmte CSS-Klasse (oder Klassen) formatieren möchtest, dann kannst Du sie hier eingeben. Die Klasse wird zusammen mit der <strong>Body-Klasse</strong> zur Klasse des <strong>Eintragscontainers</strong> hinzugefügt, wenn nur dieser Eintrag angezeigt wird (z.B. Einzelbeitrag oder Seitenansicht). Klassen können durch Leerzeichen und/oder Kommas getrennt werden.', 'upfront')
			)
		);
	}

}


upfront_register_admin_meta_box('UpFrontMetaBoxPostThumbnail');
class UpFrontMetaBoxPostThumbnail extends UpFrontAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $context;
	protected $priority;
	protected $inputs;

	public function __construct(){

		$this->id = 'post-thumbnail';		
		$this->name = __('Ausgewähltes Bild Position', 'upfront');				
		$this->context = 'side';
		$this->priority = 'low';				
		$this->inputs = array(
			'position' => array(
				'id' => 'position',
				'name' => __('Ausgewähltes Bild Position', 'upfront'),
				'type' => 'radio',
				'options' => array(
					'' => __('Verwende Block Standard', 'upfront'),
					'left' => __('Links vom Titel', 'upfront'),
					'right' => __('Rechts vom Titel', 'upfront'),
					'left-content' => __('Links vom Inhalt', 'upfront'),
					'right-content' => __('Rechts vom Inhalt', 'upfront'),
					'above-title' => __('Über dem Titel', 'upfront'),
					'above-content' => __('Über dem Inhalt', 'upfront'),
					'below-content' => __('Unter dem Inhalt', 'upfront')
				),
				'description' => __('Lege die Position des vorgestellten Bildes für diesen Eintrag fest.', 'upfront'),
				'default' => '',
				'group' => 'post-thumbnail'
			),
		);
	}

}


if ( !UpFrontSEO::is_disabled() ){
	upfront_register_admin_meta_box('UpFrontMetaBoxSEO');
}

class UpFrontMetaBoxSEO extends UpFrontAdminMetaBoxAPI {

	protected $id;
	protected $name;
	protected $post_type_supports_id;
	protected $priority;
	protected $inputs;

	public function __construct(){

		$this->id = 'seo';		
		$this->name = 'Suchmaschinenoptimierung (SEO)';			
		$this->post_type_supports_id = 'upfront-seo';		
		$this->priority = 'high';				

		$this->inputs = array(
			
			'seo-preview' => array(
				'id' => 'seo-preview',
				'type' => 'seo-preview'
			),

			
			'title' => array(
				'id' => 'title',
				'group' => 'seo',
				'name' => __('Titel', 'upfront'),
				'type' => 'text',
				'description' => __('Benutzerdefinierter <code>&lt;title&gt;</code> Tag', 'upfront')
			),

			'description' => array(
				'id' => 'description',
				'group' => 'seo',
				'name' => __('Beschreibung', 'upfront'),
				'type' => 'textarea',
				'description' => __('Benutzerdefinierte <code>&lt;meta&gt;</code> Beschreibung', 'upfront')
			),
			
			'noindex' => array(
				'id' => 'noindex',
				'group' => 'seo',
				'name' => __('<code>noindex</code> diesen Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('Index/NoIndex teilt den Engines mit, ob der Eintrag gecrawlt und zum Abrufen im Engines-Index aufbewahrt werden soll. Wenn Du dieses Kontrollkästchen aktivierst, um <code>noindex</code> zu wählen, wird der Eintrag von den Engines ausgeschlossen. <strong>Hinweis:</strong> Wenn Du nicht sicher bist, was dies bewirkt, aktiviere dieses Kontrollkästchen nicht.', 'upfront')
			),

			'nofollow' => array(
				'id' => 'nofollow',
				'group' => 'seo',
				'name' => __('<code>nofollow</code> Links in diesem Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('Follow/NoFollow teilt den Engines mit, ob Links auf dem Eintrag gecrawlt werden sollen. Wenn Du dieses Kontrollkästchen aktivierst, um "nofollow" zu verwenden, ignorieren die Engines die Links auf dem Eintrag sowohl für Erkennungs- als auch für Rankingzwecke. <strong>Hinweis:</strong> Wenn Du nicht sicher bist, was dies bewirkt, aktiviere dieses Kontrollkästchen nicht.', 'upfront')
			),

			'noarchive' => array(
				'id' => 'noarchive',
				'group' => 'seo',
				'name' => __('<code>noarchive</code> Links in diesem Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('Noarchive wird verwendet, um zu verhindern, dass Suchmaschinen eine zwischengespeicherte Kopie des Eintrags speichern. Standardmäßig behalten die Suchmaschinen sichtbare Kopien aller von ihnen indizierten Seiten bei, auf die Suchende über den "zwischengespeicherten" Link in den Suchergebnissen zugreifen können. Aktiviere dieses Kontrollkästchen, um zu verhindern, dass Suchmaschinen zwischengespeicherte Kopien dieses Eintrags speichern.', 'upfront')
			),

			'nosnippet' => array(
				'id' => 'nosnippet',
				'group' => 'seo',
				'name' => __('<code>nosnippet</code> Links in diesem Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('Nosnippet informiert die Suchmaschinen darüber, dass sie keinen beschreibenden Textblock neben dem Titel und der URL des Eintrags in den Suchergebnissen anzeigen sollen.', 'upfront')
			),

			'noodp' => array(
				'id' => 'noodp',
				'group' => 'seo',
				'name' => __('<code>noodp</code> Links in diesem Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('NoODP ist ein spezielles Tag, das den Suchmaschinen anweist, kein beschreibendes Snippet über eine Seite aus dem Open Directory-Projekt (DMOZ) zur Anzeige in den Suchergebnissen abzurufen.', 'upfront')
			),

			'noydir' => array(
				'id' => 'noydir',
				'group' => 'seo',
				'name' => __('<code>noydir</code> Links in diesem Eintrag.', 'upfront'),
				'type' => 'checkbox',
				'description' => __('NoYDir ist wie NoODP spezifisch für Yahoo! und informiert diese Engine darüber, dass Yahoo! Verzeichnisbeschreibung einer Seite/Webseite in den Suchergebnissen.', 'upfront')
			),

			'redirect-301' => array(
				'id' => 'redirect-301',
				'group' => 'seo',
				'name' => __('301 Permanente Weiterleitung', 'upfront'),
				'type' => 'text',
				'description' => __('Die permanente Weiterleitung 301 kann verwendet werden, um einen alten Beitrag oder eine alte Seite an einen neuen oder anderen Speicherort weiterzuleiten. Wenn Du jemals eine Seite verschieben oder den Permalink einer Seite änderst, leite Deine Besucher auf diese Weise an den neuen Speicherort weiter.<br /><br /><em>Wünschst Du weitere Informationen? Lese mehr über <a href="http://support.google.com/webmasters/bin/answer.py?hl=en&answer=93633" target="_blank">301 Redirects</a>.</em>', 'upfront')
			),

		);
		
	}


	protected function input_seo_preview() {

		global $post;

		$date = get_the_time('M j, Y') ? get_the_time('M j, Y') : mktime('M j, Y');
		$date_text = ( $post->post_type == 'post' ) ? $date . ' ... ' : null;

		echo '<h4 id="seo-preview-title">Vorschau der Suchmaschinenergebnisse</h4>';

			echo '<div id="upfront-seo-preview">';

				echo '<h4 title="Zum Bearbeiten anklicken">' . get_bloginfo('name') . '</h4>';
				echo '<p id="seo-preview-description" title="Zum Bearbeiten anklicken">' . $date_text . '<span id="text"></span></p>';

				echo '<p id="seo-preview-bottom"><span id="seo-preview-url">' . str_replace('http://', '', home_url()) . '</span> - <span>Cached</span> - <span>Similar</span></p>';
		
			echo '</div>';

		echo '<small id="seo-preview-disclaimer">' . __('Denke daran, dies ist nur eine vorhergesagte Vorschau der Suchmaschinenergebnisse. Es gibt keine Garantie dafür, dass es genau so aussieht. Es wird jedoch ähnlich aussehen.', 'upfront') . '</small>';

	}


	protected function input_text_with_counter($input) {

		echo '
			<tr class="label">
				<th valign="top" scope="row">
					<label for="' . $input['attr-id'] . '">' . $input['name'] . '</label>
				</th>
			</tr>

			<tr>
				<td>
					<input type="text" value="' . esc_attr($input['value']) . '" id="' . $input['attr-id'] . '" name="' . $input['attr-name'] . '" />
				</td>
			</tr>

			<tr class="character-counter">
				<td>
					<span>130</span><div class="character-counter-box"><div class="character-counter-inside"></div></div>
				</td>
			</tr>
		';

	}


	protected function modify_arguments($post = false) {

		//Do not use this box if the page being edited is the front page since they can edit the setting in the configuration.
		if ( get_option('page_on_front') == upfront_get('post') && get_option('show_on_front') == 'page' ) {

			$this->info = sprintf( __('<strong>Konfiguriere die SEO-Einstellungen für diese Seite (Startseite) auf der Registerkarte Einstellungen für die UpFront-Suchmaschinenoptimierung in <a href="%" target="_blank">UpFront &raquo; Optionen</a>.</strong>', 'upfront'), admin_url('admin.php?page=upfront-options#tab-seo') );

			$this->inputs = array();

			return;

		}

		//Setup the defaults for the title and checkboxes
		$current_screen = get_current_screen();
		$seo_templates_query = UpFrontOption::get('seo-templates', 'general', UpFrontSEO::output_layouts_and_defaults());
		$seo_templates = upfront_get('single-' . $current_screen->id, $seo_templates_query, array());

		$title_template = str_replace(array('%sitename%', '%SITENAME%'), get_bloginfo('name'), upfront_get('title', $seo_templates));

		echo '<input type="hidden" id="title-seo-template" value="' . $title_template . '" />';

		$this->inputs['noindex']['default'] = upfront_get('noindex', $seo_templates);
		$this->inputs['nofollow']['default'] = upfront_get('nofollow', $seo_templates);
		$this->inputs['noarchive']['default'] = upfront_get('noarchive', $seo_templates);
		$this->inputs['nosnippet']['default'] = upfront_get('nosnippet', $seo_templates);
		$this->inputs['noodp']['default'] = upfront_get('noodp', $seo_templates);
		$this->inputs['noydir']['default'] = upfront_get('noydir', $seo_templates);


	}

}