<?php
/**
 * UpFront Class loader
 *
 * @package UpFront
 * @subpackage   UpFront/loader
 */

global $upfront_registry;

$upfront_registry = array(

	// Abstract.
	'UpFrontAdminMetaBoxAPI'            => 'abstract/api-admin-meta-box',
	'UpFrontNotice'                     => 'abstract/notice',
	'UpFrontBlockAPI'                   => 'abstract/api-block',
	'UpFrontVisualEditorBoxAPI'         => 'abstract/api-box',
	'UpFrontVisualEditorPanelAPI'       => 'abstract/api-panel',
	'UpFrontWebFontProvider'            => 'abstract/web-fonts-api',

	// Admin.
	'UpFrontAdmin'                      => 'admin/admin',
	'UpFrontAdminBar'                   => 'admin/admin-bar',
	'UpFrontMetaBoxTemplate'            => 'admin/admin-meta-boxes',
	'UpFrontMetaBoxTitleControl'        => 'admin/admin-meta-boxes',
	'UpFrontMetaBoxDisplay'             => 'admin/admin-meta-boxes',
	'UpFrontMetaBoxPostThumbnail'       => 'admin/admin-meta-boxes',
	'UpFrontMetaBoxSEO'                 => 'admin/admin-meta-boxes',
	'UpFrontAdminPages'                 => 'admin/admin-pages',
	'UpFrontAdminWrite'                 => 'admin/admin-write',
	'UpFrontAdminInputs'                => 'admin/api-admin-inputs',

	// API.
	'UpFrontBlockOptionsAPI'            => 'api/api-block-options',
	'UpFrontChildThemeAPI'              => 'api/api-child-theme',
	'UpFrontElementAPI'                 => 'api/api-element',

	// Blocks.
	'UpFrontBlocks'                     => 'blocks/blocks',

	// Common.
	'UpFront'                           => 'common/application',
	'UpFrontBlocksAnywhere'             => 'common/blocks-anywhere',
	'UpFrontCapabilities'               => 'common/capabilities',
	'UpFrontCompiler'                   => 'common/compiler',
	'UpFrontFeed'                       => 'common/feed',
	'UpFrontCapabilities'               => 'common/capabilities',
	'UpFrontGutenbergBlocks'            => 'common/gutenberg-blocks',
	'UpFrontHttp2ServerPush'            => 'common/http2-server-push',
	'UpFrontImageResize'                => 'common/image-resizer',
	'UpFrontLayout'                     => 'common/layout',
	'UpFrontNotices'                    => 'common/notices',
	'UpFrontPlugins'                    => 'common/plugins',
	'UpFrontQuery'                      => 'common/query',
	'UpFrontResponsiveGrid'             => 'common/responsive-grid',
	'UpFrontRoute'                      => 'common/route',
	'UpFrontSchema'                     => 'common/schema',
	'UpFrontSeo'                        => 'common/seo',
	'UpFrontSettings'                   => 'common/settings',
	'UpFrontSocialOptimization'         => 'common/social-optimization',
	'UpFrontTemplates'                  => 'common/templates',
	'UpFrontMobileDetect'               => 'common/mobile-detect',
	'UpFrontCoreUpdater'                => 'common/core-updater',

	// Compatibility.
	'UpFrontCompatibilityAmember'       => 'compatibility/amember/compatibility-amember',
	'UpFrontCompatibilityDiviBuilder'   => 'compatibility/divi-builder/compatibility-divi-builder',
	'UpFrontCompatibilityHeadway'       => 'compatibility/headway/compatibility-headway',
	'UpFrontCompatibilityBlox'          => 'compatibility/blox/compatibility-blox',

	'HeadwayAdminMetaBoxAPI'          => 'compatibility/headway/abstract',
	'BloxAdminMetaBoxAPI'             => 'compatibility/blox/abstract',

	'HeadwayBlockAPI'                 => 'compatibility/headway/abstract',
	'BloxBlockAPI'                    => 'compatibility/blox/abstract',

	'HeadwayBlockOptionsAPI'          => 'compatibility/headway/abstract',
	'BloxBlockOptionsAPI'             => 'compatibility/blox/abstract',

	'HeadwayVisualEditorPanelAPI'     => 'compatibility/headway/abstract',
	'BloxVisualEditorPanelAPI'        => 'compatibility/blox/abstract',

	'UpFrontCompatibilityWooCommerce'   => 'compatibility/woocommerce/compatibility-woocommerce',
	'UpFrontCompatibilityWpml'          => 'compatibility/wpml/compatibility-wpml',

	// Data.
	'UpFrontBlocksData'                 => 'data/data-blocks',
	'UpFrontElementsData'               => 'data/data-elements',
	'UpFrontLayoutOption'               => 'data/data-layout-options',
	'UpFrontOption'                     => 'data/data-options',
	'UpFrontDataPortability'            => 'data/data-portability',
	'UpFrontSkinOption'                 => 'data/data-skin-options',
	'UpFrontDataSnapshots'              => 'data/data-snapshots',
	'UpFrontWrappersData'               => 'data/data-wrappers',

	// Display.
	'UpFrontDisplay'                    => 'display/display',
	'UpFrontGridRenderer'               => 'display/grid-renderer',
	'UpFrontHead'                       => 'display/head',
	'UpFrontLayoutRenderer'             => 'display/layout-renderer',

	// Elements.
	'UpFrontElements'                   => 'elements/elements',
	'UpFrontJSProperties'               => 'elements/js-properties',
	'UpFrontElementProperties'          => 'elements/properties',
	'UpFrontElementProperties'          => 'elements/properties',

	// Fonts.
	'UpFrontGoogleFonts'                => 'fonts/google-fonts',
	'UpFrontTraditionalFonts'           => 'fonts/traditional-fonts',
	'UpFrontFonts'                      => 'fonts/traditional-fonts',
	'UpFrontWebFontsLoader'             => 'fonts/web-fonts-loader',

	// Maintenance.
	'UpFrontMaintenance'                => 'maintenance/upgrades',

	// Media.
	'UpFrontResponsiveGridDynamicMedia' => 'media/dynamic/responsive-grid',

	// Visual Editor.
	'UpFrontVisualEditorDisplay'        => 'visual-editor/display',
	'UpFrontIframeDummyContent'         => 'visual-editor/dummy-content',
	'UpFrontVisualEditorIframeGrid'     => 'visual-editor/iframe-grid',
	'UpFrontLayoutSelector'             => 'visual-editor/layout-selector',
	'UpFrontVisualEditorPreview'        => 'visual-editor/preview',
	'UpFrontVisualEditorAJAX'           => 'visual-editor/visual-editor-ajax',
	'UpFrontVisualEditor'               => 'visual-editor/visual-editor',
	'UpFrontGridManagerBox'             => 'visual-editor/boxes/grid-manager',
	'UpFrontSnapshotsBox'               => 'visual-editor/boxes/snapshots',
	'UpFrontPropertyInputs'             => 'visual-editor/panels/design/property-inputs',
	'UpFrontSidePanelDesignEditor'      => 'visual-editor/panels/design/side-panel-design-editor',
	'GridSetupPanel'                  => 'visual-editor/panels/grid/setup',

	// Widgets.
	'UpFrontWidgets'                    => 'widgets/widgets',

	// Wrappers.
	'UpFrontWrapperOptions'             => 'wrappers/wrapper-options',
	'UpFrontWrappers'                   => 'wrappers/wrappers',

);

$upfront_registry = apply_filters( 'upfront_class_registry', $upfront_registry );

spl_autoload_register(
	function ( $class ) {

		if ( strpos( $class, 'UpFront' ) === 0 ) {

			global $upfront_registry;
			$file = '';

			if ( isset( $upfront_registry[ $class ] ) ) {
				$file = $upfront_registry[ $class ];
			}

			if ( ! is_file( $file ) ) {
				$file = dirname( __FILE__ ) . '/' . $file . '.php';
			}

			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}
);