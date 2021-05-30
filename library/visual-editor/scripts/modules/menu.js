define(['jquery', 'util.tour', 'helper.codeMirror', 'deps/url'], function($, tour, codeMirrorHelper, url) {

    var menu = {
        init: function() {
            menu.bind();
        },

        bind: function() {

            /* MODE SWITCHING */
            $('ul#modes li').on('click', function() {
                $(this).siblings('li').removeClass('active');
                $(this).addClass('active');
            });

            $('ul#modes li a').bind('click', function() {

                var modeURL = new Url($(this).attr('href'));
                modeURL.query['ve-layout'] = UpFront.viewModels.layoutSelector.currentLayout();

                $(this).attr('href', modeURL.toString());

            });
            /* END MODE SWITCHING */

            /* VIEW SITE BUTTON */
            $('#menu-link-view-site a').bind('click', function() {

                var siteURL = new Url(UpFront.homeURL);
                siteURL.query['upfront-trigger'] = 'layout-redirect';
                siteURL.query['layout'] = UpFront.viewModels.layoutSelector.currentLayout();

                $(this).attr('href', siteURL.toString());


            });
            /* END VIEW SITE BUTTON */

            /* SAVE BUTTON */
            $('span#save-button').click(function() {

                save();

                return false;

            });
            /* END SAVE BUTTON */

            /* SNAPSHOTS */
            $('#snapshots-button').bind('click', function() {

                openBox('snapshots');

            });
            /* END SNAPSHOTS */

            /* TOOLS */
            $('#tools-tour').bind('click', tour.start);

            $('#tools-grid-manager').bind('click', function() {

                hidePanel();

                openBox('grid-manager');

            });

            $('#open-live-css').bind('click', function() {

                codeMirrorHelper.showEditor('live-css', 'css', $('textarea#live-css-content').val(), function(editor) {

                    // Data Handle for textarea
                    var textarea = $('textarea#live-css-content');
                    // Get CSS changes
                    var cssChanges = editor.getValue();

                    // Set CSS changes to content, holder and textarea
                    $i('style#live-css-holder').html(cssChanges);
                    textarea.text(cssChanges);
                    localStorage['upfront-visual-editor-live-css-content'] = btoa(unescape(encodeURIComponent(cssChanges)));
                    dataHandleInput(textarea);
                    allowSaving();

                });

            });

            $('#tools-clear-cache').bind('click', function() {

                /* Set up parameters */
                var parameters = {
                    security: UpFront.security,
                    action: 'upfront_visual_editor',
                    method: 'clear_cache'
                };

                /* Do the stuff */
                $.post(UpFront.ajaxURL, parameters, function(response) {

                    if (response === 'success') {

                        showNotification({
                            id: 'cache-cleared',
                            message: 'Der Cache wurde erfolgreich geleert!',
                            success: true
                        });

                    } else {

                        showErrorNotification({
                            id: 'error-could-not-clear-cache',
                            message: 'Fehler: Cache konnte nicht gelöscht werden.'
                        });

                    }

                });

            });
            /* END TOOLS */

            /*	Mobile menu	*/

            var event = !UpFront.touch ? 'click' : 'touchstart';

            $(document).on(event, '#mobile-menu', function() {

                var menu = $('#menu');
                if (menu.hasClass('open')) {
                    menu.removeClass('open');
                } else {
                    menu.addClass('open');
                }

            });

            /*	END Mobile menu	*/
        }

    }

    return menu;


});