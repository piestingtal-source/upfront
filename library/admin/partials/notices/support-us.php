<?php defined( 'ABSPATH' ) or exit; ?>

<div class="notice notice-info upfront-unlimited-notice-rate">

	<img alt="UpFront Unlimited" src="<?php echo get_template_directory_uri() . '/library/admin/images/upfront-theme-logo-square-250.png'; ?>" class="avatar avatar-120 photo" height="120" width="120">

	<div class="upfront-unlimited-notice-rate-content">

		<div class="upfront-unlimited-notice-rate-content-text">
			<p><?php _e( 'Hallo', 'upfront' ); ?>,</p>
			<p><?php _e( 'Unser Team hat sehr hart gearbeitet, um Dir dieses leistungsstarke Tool zur Verfügung zu stellen. Wir hoffen, dass es dir gefällt.', 'upfront' ); ?></p>
			<h4><?php _e( 'Ziel ist es zusammenzuarbeiten?', 'upfront' ); ?></h4>
			<p><?php _e( 'Registriere Dich, um noch mehr aus UpFront heraus zu holen', 'upfront' ); ?></p>
			<ul>				
				<li><?php _e( '- Fehler melden: https://n3rds.work/psource-bugtracking/', 'upfront' ); ?></li>
				<li><?php _e( '- Lerne mehr über UpFront', 'upfront' ); ?></li>
				<li><?php _e( '- Schlage Funktionen, Blöcke oder Plugins vor', 'upfront' ); ?></li>
				<li><?php _e( '- Trete unserer Community bei', 'upfront' ); ?></li>
				<li><?php _e( '- Teile UpFront mit Kollegen und Freunden', 'upfront' ); ?></li>
				<li><?php _e( '- Verbreite das Wort!', 'upfront' ); ?></li>
			</ul>			
			<p><?php _e( 'Lasst uns zusammen bauen!', 'upfront' ); ?></p>
			<p><?php _e( '@UpFrontTeam', 'upfront' ); ?></p>
		</div>

		<p class="upfront-unlimited-notice-rate-actions">			
			<a href="https://n3rds.work/memberships/" class="button button-primary" target="_blank"><?php _e( 'Mitglied werden', 'upfront' ); ?></a>						
			<a href="<?php echo self::get_dismiss_link(); ?>" class="upfront-unlimited-notice-rate-dismiss"><?php _e( 'Verwerfen', 'upfront' ); ?></a>
		</p>

	</div>

</div>

<style>
	.upfront-unlimited-notice-rate {
		position: relative;
		padding: 15px 20px;
	}
	.upfront-unlimited-notice-rate .avatar {
		position: absolute;
		left: 20px;
		top: 20px;
	}
	.upfront-unlimited-notice-rate-content {
		margin-left: 140px;
	}
	.upfront-unlimited-notice-rate-content-text p {
		font-size: 15px;
	}
	p.upfront-unlimited-notice-rate-actions {
		margin-top: 15px;
	}
	p.upfront-unlimited-notice-rate-actions a {
		vertical-align: middle !important;
	}
	p.upfront-unlimited-notice-rate-actions a + a {
		margin-left: 20px;
	}
	.upfront-unlimited-notice-rate-dismiss {
		position: absolute;
		top: 10px;
		right: 10px;
		padding: 10px 15px 10px 21px;
		font-size: 13px;
		line-height: 1.23076923;
		text-decoration: none;
	}
	.upfront-unlimited-notice-rate-dismiss:before {
		position: absolute;
		top: 8px;
		left: 0;
		margin: 0;
		-webkit-transition: all .1s ease-in-out;
		transition: all .1s ease-in-out;
		background: 0 0;
		color: #b4b9be;
		content: "\f153";
		display: block;
		font: 400 16px / 20px dashicons;
		height: 20px;
		text-align: center;
		width: 20px;
	}
	.upfront-unlimited-notice-rate-dismiss:hover:before {
		color: #c00;
	}
</style>
