require.config({
    paths: {
        knockout: 'deps/knockout',
        underscore: 'deps/underscore',
        jquery: 'jquery.min',

        /* jQuery Plugins */
        jqueryUI: 'deps/jquery.ui',
        qtip: 'deps/jquery.qtip',
        jbox: 'deps/jBox/dist/jBox.all',
    },
    shim: {
        underscore: {
            exports: '_'
        }
    }
});

require(['jquery', 'util.loader'], function($) {

    /* Start loading indidcator */
    startTitleActivityIndicator();
    //iframe.showIframeLoadingOverlay();

    /* Parse the JSON in the UpFront l10n array */
    UpFront.blockTypeURLs = $.parseJSON(UpFront.blockTypeURLs.replace(/&quot;/g, '"'));
    UpFront.allBlockTypes = $.parseJSON(UpFront.allBlockTypes.replace(/&quot;/g, '"'));
    UpFront.ranTour = $.parseJSON(UpFront.ranTour.replace(/&quot;/g, '"'));
    UpFront.designEditorProperties = $.parseJSON(UpFront.designEditorProperties.replace(/&quot;/g, '"'));
    UpFront.layouts = $.parseJSON(UpFront.layouts.replace(/&quot;/g, '"'));

    /* Setup modules */
    require(['modules/layout-selector'], function(layoutSelector) {
        layoutSelector.init();
    });

    require(['modules/panel', 'modules/iframe'], function(panel, iframe) {
        panel.init();
        iframe.init();
    });

    require(['modules/menu'], function(menu) {
        menu.init();
    });

    require(['modules/snapshots'], function(snapshots) {
        snapshots.init();
    });

    /* Init tour */
    require(['util.tour'], function(tour) {

        if (UpFront.ranTour[UpFront.mode] == false && UpFront.ranTour.legacy == false) {
            tour.start();
        }

    });

    /**
     *
     * Load mode switcher
     *
     */
    require(['switch.mode'], function(switchMode) {
        switchMode.init();
    });


    /* Load helpers all at once since they're used everywhere */
    require(['helper.data', 'helper.blocks', 'helper.wrappers', 'helper.context-menus', 'helper.notifications', 'helper.boxes', 'helper.history'], function(data, blocks, wrappers, contextMenus, notifications, boxes, history) {
        history.init();
    });


    /**
     *
     * Offline check
     *
     */
    require(['util.offline'], function(offline) {
        offline.init();
    });



    /* Load in the appropriate modules depending on the mode */
    switch (UpFront.mode) {

        case 'grid':

            require(['modules/grid/mode-grid', 'modules/iframe', 'modules/layout-selector'], function(modeGrid) {

                UpFront.instance = modeGrid;

                modeGrid.init();
                waitForIframeLoad(modeGrid.iframeCallback);
            });

            break;

        case 'design':

            require(['modules/design/mode-design', 'util.preview', 'modules/iframe', 'modules/layout-selector'], function(modeDesign, devicePreview) {
                //require(['modules/design/mode-design', 'util.preview', 'modules/content-selector', 'modules/iframe', 'modules/layout-selector'], function(modeDesign, devicePreview, contentSelector) {

                /**
                 *
                 * Load Devices Preview 
                 *
                 */
                devicePreview.init();


                UpFront.instance = modeDesign;

                modeDesign.init();
                waitForIframeLoad(modeDesign.iframeCallback);

                /*
                	- Disable until future release
				
                contentSelector.init();
                if(UpFront.viewModels.layoutSelector.currentLayout().search('template') != -1){
                	$('#content-selector-select').show();
                }else{
                	$('#content-selector-select').hide();
                }
                */
            });

            break;

    }

    /* After everything is loaded show the Visual Editor */
    $(document).ready(function() {

        $('body').addClass('show-ve');

    });

    $(window).bind('load', function() {

        /* Remove VE loader overlay after we know page has loaded */
        setTimeout(function() {
            $('div#ve-loading-overlay').remove();
        }, 1000);

    });


});