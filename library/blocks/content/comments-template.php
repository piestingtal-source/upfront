<?php
global $post;
global $upfront_comments_template_args;

echo '<div id="comments">';

	if ( !post_password_required() ) { 

		if ( have_comments() ) {

			/* Comments Area Heading */
			echo '<h3 id="comments">';

				/* Comments Area Responses Formatting */
					$comments_number = (int)get_comments_number($post->ID);

					if ( $comments_number == 1  ) 
						$comments_heading_responses_format = stripslashes(upfront_get('comments-area-heading-responses-number-1', $upfront_comments_template_args, __('Eine Antwort', 'upfront') ));
					else
						$comments_heading_responses_format = stripslashes(upfront_get('comments-area-heading-responses-number', $upfront_comments_template_args, '%num% ' . __('Antworten', 'upfront') ));

					$comments_heading_replacements = array(
						'responses' => str_replace('%num%', $comments_number, $comments_heading_responses_format),
						'title' => get_the_title()
					);
				/* End Comments Area Responses Formatting */

				echo str_replace(array('%responses%', '%title%'), $comments_heading_replacements, upfront_get('comments-area-heading', $upfront_comments_template_args, '%responses% zu <em>%title%</em>'));

			echo '</h3>';
			/* End Comments Area Heading */

			echo '<ol class="commentlist">';

				wp_list_comments(apply_filters('upfront_comments_args', array(
					'avatar_size' => 44,
					'format' => 'html5'
				))); 

			echo '</ol>';

			echo '<div class="comments-navigation">';
				echo '<div class="alignleft">';
					paginate_comments_links();
				echo '</div>';
			echo '</div>';

		} else {

			if ( $post->comment_status != 'open' ) {

				if ( is_single() ) {

					$comments_closed = apply_filters('upfront_comments_closed', __('Sorry, Kommentare sind für diesen Beitrag geschlossen.', 'upfront'));

					echo '<p class="comments-closed">' . $comments_closed . '</p>';

				}

			}

		}

		comment_form(apply_filters('upfront_comment_form_args', array()));

	} else {

		echo '<p class="nocomments">' . __('Dieser Beitrag ist passwortgeschützt. Bitte gib das Passwort ein, um die Kommentare anzuzeigen.', 'upfront') . '</p>';

	}

echo '</div>';