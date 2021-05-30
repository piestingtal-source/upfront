<?php

defined('ABSPATH') or die("Bitte keine Script-Kiddies!");

?>
<div class="upfront-admin-container">

	<img class="upfront-logo" src="<?php echo upfront_url() . '/library/admin/images/upfront-theme-logo-square-250.png'; ?>">

	<div class="upfront-admin-row menu">
		<a href="javascript:void(0)" onclick="openTabAdmin(event, 'welcome');">
			<div class="upfront-admin-title tablink upfront-admin-border-red"><?php _e('Willkommen', 'upfront'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'options');">
			<div class="upfront-admin-title tablink"><?php _e('Optionen', 'upfront'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'need-help');">
			<div class="upfront-admin-title tablink"><?php _e('Brauchst Du Hilfe?', 'upfront'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'unlimited-growth');">
			<div class="upfront-admin-title tablink"><?php _e('Unbegrenzte Möglichkeiten', 'upfront'); ?></div>
		</a>
	</div>

	<div id="welcome" class="upfront-admin-tab" style="">	
		<div class="content">

			<h1><?php _e('Willkommen!', 'upfront'); ?></h1>

			<p><?php _e('Deine <strong>UpFront Theme Framework</strong> Installation ist betriebsbereit.', 'upfront'); ?></p>
			
			<p><strong><?php _e('Beginne jetzt mit dem Erstellen!', 'upfront'); ?></strong></p>

			<br>

			<p><?php _e('Um diese Seite auszublenden, ändere einfach die Standard-Administrationsseite in <a href="?page=upfront-options">UpFront » Optionen</a>.', 'upfront'); ?></p>

			<div class="separator"></div>

			<h2><?php _e('UpFront Kernfunktionen im WordPress-Dashboard/Admin-Menü.', 'upfront'); ?></h2>
			<div class="box">
				<h3><?php _e('UpFront - Willkommen!', 'upfront'); ?></h3>
				<p><?php _e('(Diese Seite)', 'upfront'); ?></p>
				<p><?php _e('Zugriff erhalten auf ►', 'upfront'); ?></p>
				<p><?php _e('Allgemeine Informationen, Dokumentation und Support für <b>UpFront</b> Theme Framework.', 'upfront'); ?></p>
				<p><?php _e('Blöcke und Vorlagen erweitern die Möglichkeiten von <b>UpFront</b> Theme Framework.', 'upfront'); ?></p>
			</div>
			

			<h2><?php _e('UpFront-Starterbenutzer', 'upfront'); ?></h2>
			<div class="box">
				<p><?php _e('Bitte lies die UpFront Theme Framework <a href="https://n3rds.work/">Dokumentation</a>.', 'upfront'); ?></p>
			</div>


			<div class="box">
				<h3><?php _e('Visueller UpFront Editor', 'upfront'); ?></h3>
				
				<p><?php _e('Der Visuelle UpFront Editor ist ein leistungsstarkes Tool zum Entwerfen von Layouts und Vorlagen für WordPress-Webseiten. Passe fast jedes visuelle Element Deiner Webseiten einfach über eine grafische Oberfläche an (Code kann bei Bedarf einfach mit dem integrierten Code-Editor hinzugefügt werden).', 'upfront'); ?></p>

				<p><?php _e('Lerne mehr über diese Plattform in unserer <a rel="noopener" href="https://n3rds.work">"Anleitung zum Visuellen UpFront Editor". </a>', 'upfront'); ?></p>

				<a href="<?php echo home_url() . '/?visual-editor=true'; ?>" class="access-to-unlimited-editor"><span class="text"><?php _e('Zugriff auf <b>UpFront</b> Editor', 'upfront'); ?></span><span class="line -right"></span><span class="line -top"></span><span class="line -left"></span><span class="line -bottom"></span></a>
			</div>
			<div class="box">
				<h3><?php _e('Zusätzliche Blöcke verfügbar!', 'upfront'); ?></h3>
				<p><?php _e('Mit einer Mitgliedschaft im N3rds@Work Netzwerk erhältst Du ausserdem Zugriff auf weitere Blöcke und Vorlagen.', 'upfront'); ?></p>
				<a href="https://n3rds.work/memberships/"><?php _e('Registriere Dich bei WMS N@W, um Zugriff auf zusätzliche Plugins und Blöcke zu erhalten', 'upfront'); ?></a>				
			</div>
		</div>
	</div>

	<div id="options" class="upfront-admin-tab" style="display:none">
		<div class="content">
			<div class="box">
				<h3><?php _e('UpFront | Optionen', 'upfront'); ?></h3>
				<p><?php _e('Richte Google Analytics, SEO, Favoriten und andere erweiterte Einstellungen ein.', 'upfront'); ?></p>
			</div>
			<h2 class="center"><?php _e('UpFront | Werkzeuge', 'upfront'); ?></h2>
			<div class="box">
				<h3><?php _e('Systeminformationen.', 'upfront'); ?></h3>
				<p><?php _e('Gib diese Systeminformationen an, um ein Ticket zu öffnen oder eine Hilfeanforderung in den Foren zu protokollieren.', 'upfront'); ?></p>
			</div>
			<div class="box">
				<h3><?php _e('Snapshots', 'upfront'); ?></h3>
				<p><?php _e('Lösche UpFront Snapshots des Theme Frameworks, um Speicherplatz freizugeben.', 'upfront'); ?></p>
			</div>
			<div class="box">
				<h3><?php _e('Zurücksetzen', 'upfront'); ?></h3>
				<p><?php _e('Anweisungen zum Zurücksetzen Deiner UpFront Theme Framework Installation.', 'upfront'); ?></p>
			</div>
		</div>
	</div>

	<div id="need-help" class="upfront-admin-tab" style="display:none">
		<div class="content">
			<h2 class="center"><?php _e('Help', 'upfront'); ?></h2>
			<p><?php _e('UpFront | Unlimited Theme Builder provides professional support and comprehensive documentation to help you bring your projects alive.', 'upfront'); ?></p>			
			<div class="separator"></div>
			<div class="box">
				<h3><?php _e('UpFront | Community', 'upfront'); ?></h3>
				<p><?php _e('Join our community, get involved and help each other across multiple channels.', 'upfront'); ?></p>
				<p><a href="https://www.facebook.com/upfrontunlimited/" target="_blank"><?php _e('Facebook Page', 'upfront'); ?></a></p>
			</div>
			<div class="box">
				<h3><?php _e('UpFront | Documentation', 'upfront'); ?></h3>
				<p><?php _e('Register with us and get free access to our in- depth documentation. <a target="_blank" href="https://docs.upfrontunlimited.com/" rel="noopener">docs.upfrontunlimited.com</a>', 'upfront'); ?></p>
			</div>
		</div>
	</div>


</div>