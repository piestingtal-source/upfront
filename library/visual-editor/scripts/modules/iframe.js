define(['jquery', 'deps/itstylesheet', 'util.saving', 'util.usability', 'util.tooltips'], function($, itstylesheet, saving) {

    $i = function(element) {

        if (typeof UpFront.iframe == 'undefined' || typeof UpFront.iframe.contents() == 'undefined')
            return $();

        return UpFront.iframe.contents().find(element);

    }

    $iDocument = function() {

        return $(UpFront.iframe.contents());

    }


    loadIframe = function(callback, url) {

        if (typeof url == 'undefined' || !url)
            var url = UpFront.homeURL;

        /* Choose contents iframe or preview iframe depending on argument */
        var iframe = UpFront.iframe;

        /* Make the title talk */
        startTitleActivityIndicator();
        showIframeLoadingOverlay();

        /* Close Grid Manager */
        closeBox('grid-manager');

        /* Build the URL */
        iframeURL = url;
        iframeURL = updateQueryStringParameter(iframeURL, 've-iframe', 'true');
        iframeURL = updateQueryStringParameter(iframeURL, 've-layout', encodeURIComponent(UpFront.viewModels.layoutSelector.currentLayout()));
        iframeURL = updateQueryStringParameter(iframeURL, 've-layout-customized', UpFront.viewModels.layoutSelector.currentLayoutCustomized());
        iframeURL = updateQueryStringParameter(iframeURL, 've-iframe-mode', UpFront.mode);
        iframeURL = updateQueryStringParameter(iframeURL, 'rand', Math.floor(Math.random() * 100000001));

        /* Clear out existing iframe contents */
        if (iframe.contents().find('.ui-upfront-grid').length && typeof iframe.contents().find('.ui-upfront-grid').upfrontGrid != 'undefined') {
            iframe.contents().find('.ui-upfront-grid').upfrontGrid('destroy');
        }

        iframe.contents().find('*')
            .off()
            .remove();

        iframe[0].src = iframeURL;
        waitForIframeLoad(callback, iframe);

    }


    waitForIframeLoad = function(callback, iframeEl) {

        if (typeof iframeEl == 'undefined' || !iframeEl) {
            var iframeEl = UpFront.iframe;
        }

        /* Setup timeout */
        if (typeof iframeTimeout == 'undefined') {
            iframeTimeout = setTimeout(iframe.loadTimeout, 40000);
        }

        /* Check if iframe body has iframe-loaded class which is added via inline script in the footer of the iframe */
        if (typeof iframeEl == 'undefined' || iframeEl.contents().find('body.iframe-loaded').length != 1) {
            return setTimeout(function() {
                waitForIframeLoad(callback, iframeEl);
            }, 100);
        }

        /* Cancel out timeout callback */
        clearTimeout(iframeTimeout);

        return iframe.loadCallback(callback);

    }


    showIframeOverlay = function() {

        var overlay = $('div#iframe-overlay');
        overlay.show();

    }


    hideIframeOverlay = function(delay) {

        if (typeof delay != 'undefined' && delay == false)
            return $('div#iframe-overlay').hide();

        /* Add a timeout for intense draggers */
        setTimeout(function() {
            $('div#iframe-overlay').hide();
        }, 250);

    }


    showIframeLoadingOverlay = function() {

            /* Restrict scrolling */
            $('div#iframe-container').css('overflow', 'hidden');

            /* Position loading overlay */
            $('div#iframe-loading-overlay').css({
                top: $('div#iframe-container').scrollTop()
            });

            /* Only show if not already visible */
            if (!$('div#iframe-loading-overlay').is(':visible')) {
                createCog($('div#iframe-loading-overlay'), true);
                $('div#iframe-loading-overlay').show();
            }

            return $('div#iframe-loading-overlay');

        },


        hideIframeLoadingOverlay = function() {

            $('div#iframe-container').css('overflow', 'auto');
            $('div#iframe-loading-overlay').hide().html('');

        }


    var iframe = {
        init: function() {

            $(document).ready(function() {

                UpFront.iframe = $('iframe#content');

                iframe.bindFocusBlur();

            });

        },

        bindFocusBlur: function() {

            UpFront.iframe.on('mouseleave', function() {
                $(this).trigger('blur');

                /* Hide any tooltips */
                $i('[data-hasqtip]').qtip('disable', true);
            });

            UpFront.iframe.on('mouseenter mousedown', function() {
                //If there is another textarea/input that's focused, don't focus the iframe.
                if ($('textarea:focus, input:focus').length === 1)
                    return;

                $i('[data-hasqtip]').qtip('enable');
                $(this).trigger('focus');
            });

        },

        loadCallback: function(callback) {

            clearUnsavedValues();

            /* Fire callback if it exists */
            if (typeof callback === 'function')
                callback();

            iframe.defaultLoadCallback();

            iframe.stopFirefoxLoadingIndicator();

            /* Fire callback! */
            $('body').triggerHandler('upfrontIframeLoad');

            return true;

        },

        defaultLoadCallback: function() {

            stopTitleActivityIndicator();

            changeTitle('Visueller Editor: ' + UpFront.viewModels.layoutSelector.currentLayoutName());
            $('span#current-layout').text(UpFront.viewModels.layoutSelector.currentLayoutName());

            /* Set up tooltips */
            setupTooltips();
            setupTooltips('iframe');
            /* End Tooltips */

            /* Stylesheets for more accurate live designing */
            /* Main UpFront stylesheet, used primarily by design editor */
            stylesheet = new ITStylesheet({ document: UpFront.iframe.contents()[0], href: UpFront.homeURL + '/?upfront-trigger=compiler&file=general-design-editor' }, 'find');

            /* Catch-all adhoc stylesheet used for overriding */
            css = new ITStylesheet({ document: UpFront.iframe.contents()[0] }, 'load');
            /* End stylesheets */

            /* Hide iframe overlay if it exists */
            hideIframeOverlay(false);

            $('#iframe-notice').remove();

            /* Add the template notice if it's layout mode and a template is active */
            if (UpFront.viewModels.layoutSelector.currentLayoutTemplate() && UpFront.mode == 'grid') {

                showIframeOverlay();

                var $iframeNotice = $('<div id="iframe-notice">' +
                    '<div>' +
                    '<h1>This layout currently has a Shared Layout assigned to it.</h1>' +
                    '<h3>The shared layout assigned is <strong>' + UpFront.viewModels.layoutSelector.currentLayoutTemplateName() + '</strong></h3>' +
                    '<p><span class="button button-blue" id="iframe-notice-switch-to-shared-layout">Wechseln zu gemeinsamen Layout</span><span class="button button-blue" id="iframe-notice-unassign-shared-layout">Freigegebenes Layout aufheben</span></p>' +
                    '</div>' +
                    '</div>');

                $iframeNotice.appendTo($('#iframe-container'));

                $iframeNotice.on('click', '#iframe-notice-unassign-shared-layout', function() {

                    return unassignSharedLayout(UpFront.viewModels.layoutSelector.currentLayout(), false, UpFront.viewModels.layoutSelector.currentLayoutName());

                });

                $iframeNotice.on('click', '#iframe-notice-switch-to-shared-layout', function() {

                    switchToLayout('template-' + UpFront.viewModels.layoutSelector.currentLayoutTemplate().replace('template-', ''), true, UpFront.viewModels.layoutSelector.currentLayoutTemplateName());

                });


            }
            /* Disallow certain keys so user doesn't accidentally leave the VE */
            disableBadKeys();

            /* Bind visual editor key shortcuts */
            bindKeyShortcuts();

            /* Funnel any keydown, keypress, keyup events to the parent window */
            $i('html, body').bind('keydown', function(event) {
                $(document).trigger(event);
                event.stopPropagation();
            });

            $i('html, body').bind('keypress', function(event) {
                $(document).trigger(event);
                event.stopPropagation();
            });

            $i('html, body').bind('keyup', function(event) {
                $(document).trigger(event);
                event.stopPropagation();
            });

            /* Deactivate all links and buttons */
            if (UpFront.touch)
                UpFront.iframe.contents().find('body').css('-webkit-touch-callout', 'none');

            UpFront.iframe.contents().find('body').delegate('a, input[type="submit"], button, span', 'click', function(event) {

                if ($(this).hasClass('allow-click'))
                    return;

                event.preventDefault();

                return false;

            });

            /* Show the load message */
            if (typeof upfrontIframeLoadNotification !== 'undefined') {
                showNotification({
                    id: 'iframe-load-notification',
                    message: upfrontIframeLoadNotification,
                    overwriteExisting: true
                });

                delete upfrontIframeLoadNotification;
            }

            /* Remove the tabs that are set to close on layout switch */
            removeLayoutSwitchPanels();

            /* Show the grid wizard if the current layout isn't customized and not using a tmeplate */
            var layoutNode = $('div#layout-selector span.layout[data-layout-id="' + UpFront.viewModels.layoutSelector.currentLayout() + '"]');
            var layoutLi = layoutNode.parent();

            if (!$i('.block').length &&
                !(UpFront.viewModels.layoutSelector.currentLayoutCustomized() && UpFront.viewModels.layoutSelector.currentLayout().indexOf('template-') !== 0) &&
                !UpFront.viewModels.layoutSelector.currentLayoutTemplate() &&
                UpFront.mode == 'grid' &&
                UpFront.viewModels.layoutSelector.currentLayoutInUse() != UpFront.viewModels.layoutSelector.currentLayout() &&
                UpFront.viewModels.layoutSelector.currentLayout().indexOf('template-') === -1
            ) {

                hidePanel();

                showIframeOverlay();

                var $iframeNotice = $('<div id="iframe-notice">' +
                    '<div>' +
                    '<h1>Dieses Layout erbt von einem anderen Layout.</h1>' +
                    '<h3>Das geerbte Layout ist <strong>' + UpFront.viewModels.layoutSelector.currentLayoutInUseName() + '</strong></h3>' +
                    '<p><span class="button button-blue" id="iframe-notice-customize-current">Passe das aktuelle Layout an</span><span class="button button-blue" id="iframe-notice-switch-to-inherited">Wechseln zu vererbten Layout</span></p>' +
                    '	</div>' +
                    '</div>');

                $iframeNotice.appendTo('#iframe-container');

                $iframeNotice.on('click', '#iframe-notice-customize-current', function() {

                    $('#iframe-notice').remove();

                    hideIframeOverlay();
                    if (typeof openBox !== 'undefined') {
                        openBox('grid-manager');
                    }

                });

                $iframeNotice.on('click', '#iframe-notice-switch-to-inherited', function() {

                    switchToLayout(UpFront.viewModels.layoutSelector.currentLayoutInUse(), true, UpFront.viewModels.layoutSelector.currentLayoutInUseName());

                });

            } else if (UpFront.viewModels.layoutSelector.currentLayoutCustomized() || UpFront.viewModels.layoutSelector.currentLayoutTemplate()) {

                if (typeof closeBox !== 'undefined') {
                    closeBox('grid-manager');
                }

            } else {

                if (typeof openBox !== 'undefined') {
                    openBox('grid-manager');
                }

            }

            /* Clear out and disable iframe loading indicator */
            hideIframeLoadingOverlay();

        },

        loadTimeout: function() {

            iframeTimeout = true;

            stopTitleActivityIndicator();

            changeTitle('Visueller Editor: Fehler!');

            /* Hide all controls */
            $('#iframe-container, #menu, #panel, #layout-selector-offset').hide();

            alert("FEHLER: Beim Laden des visuellen Editors ist ein Problem aufgetreten.\n\nDein Browser wird automatisch aktualisiert, um den Ladevorgang erneut zu versuchen.");

            document.location.reload(true);

        },

        stopFirefoxLoadingIndicator: function() {

            //http://www.shanison.com/2010/05/10/stop-the-browser-%E2%80%9Cthrobber-of-doom%E2%80%9D-while-loading-comet-forever-iframe/
            if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {

                var fake_iframe;

                if (fake_iframe == null) {
                    fake_iframe = document.createElement('iframe');
                    fake_iframe.style.display = 'none';
                }

                document.body.appendChild(fake_iframe);
                document.body.removeChild(fake_iframe);

            }

        }

    }

    return iframe;

});