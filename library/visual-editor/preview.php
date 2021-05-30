<?php
class UpFrontVisualEditorPreview {

	public static function remove_preview_options() {

		if ( !UpFrontCapabilities::can_user_visually_edit() )
			return;

		//Ruft alle Optionen in wp_options ab und entfernt die vorschau-spezifischen Optionen
		foreach ( wp_load_alloptions() as $option => $option_value ) {

			//Diese if-Anweisung ist unglaublich wichtig und darf nicht manipuliert werden. Sie muss bei Änderungen dreifach überprüft werden.
			if ( preg_match('/^upfront_(.*)?_preview$/', $option) && strpos($option, 'upfront_') === 0 && strpos($option, '_preview') !== false ) {
				delete_option($option);
			}

		}

	}

}