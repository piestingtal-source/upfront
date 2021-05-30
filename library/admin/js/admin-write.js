(function($) {
    $(document).ready(function() {

        /* SEO Live Preview */
        if ($('div#upfront-seo-preview').length == 1) {

            var isClassicEditor = false;

            //Insert content into preview
            if ($('textarea#upfront-admin-meta-box-seo-description').val().length > 0) {

                var description = $('textarea#upfront-admin-meta-box-seo-description').val();

                if (description.length > 150) {
                    description = description.substr(0, 150) + ' ...';
                }

                $('div#upfront-seo-preview p#seo-preview-description span#text').text(description);

            } else {

                var content = $('textarea#content').val()

                if (typeof content !== 'undefined') {

                    var excerpt = $('textarea#content').val().replace(/(<([^>]+)>)/ig, '');

                    if (excerpt.length > 150) {
                        excerpt = excerpt.substr(0, 150) + ' ...';
                    }

                    $('div#upfront-seo-preview p#seo-preview-description span#text').text(excerpt);
                }


            }


            if ($('input#upfront-admin-meta-box-seo-title').val().length > 0) {

                $('div#upfront-seo-preview h4').text($('input#upfront-admin-meta-box-seo-title').val());

            } else if (typeof $('div#titlediv input#title').val() != 'undefined') {

                if ($('div#titlediv input#title').val().length > 0) {
                    $('div#upfront-seo-preview h4').text($('input#title-seo-template').val().replace('%title%', $('div#titlediv input#title').val()));
                }


            }
            if ($('span#upfront-seo-preview-url').text().length == 0) {

                $('span#seo-preview-url').text($('span#sample-permalink').text().replace('http://', ''));

            }


            //Bind Inputs
            $('input#upfront-admin-meta-box-seo-title').bind('keyup blur', function() {

                if ($(this).val().length > 0) {

                    $('div#upfront-seo-preview h4').text($(this).val());

                } else {

                    $('div#upfront-seo-preview h4').text($('input#title-seo-template').val().replace('%title%', $('div#titlediv input#title').val()));

                }

            });

            $('textarea#upfront-admin-meta-box-seo-description').bind('keyup blur', function() {

                var description = $(this).val();

                if (description.length > 150)
                    description = description.substr(0, 150) + ' ...';

                if ($(this).val().length == 0) {

                    var description = $('textarea#content').val().replace(/(<([^>]+)>)/ig, '');

                    if (excerpt.length > 150) {
                        description = excerpt.substr(0, 150) + ' ...';
                    }

                }

                $('div#upfront-seo-preview p#seo-preview-description span#text').text(description);

            });


            $('div#titlediv input#title').bind('keyup blur', function() {

                if ($('input#upfront-admin-meta-box-seo-title').val().length == 0) {

                    $('div#upfront-seo-preview h4').text($('input#title-seo-template').val().replace('%title%', $(this).val()));

                }

            });


            //Periodically check for updates in the content
            setInterval(function() {

                if ($('textarea#upfront-admin-meta-box-seo-description').val().length > 0)
                    return false;

                if ($('textarea#content').length > 0) {
                    var excerpt = $('textarea#content').val().replace(/(<([^>]+)>)/ig, '');
                } else {
                    if ($('textarea#excerpt').length > 0) {
                        var excerpt = $('textarea#excerpt').val();
                    } else {
                        var excerpt = '';
                    }
                }

                if (excerpt.length > 150)
                    excerpt = excerpt.substr(0, 150) + ' ...';

                $('div#upfront-seo-preview p#seo-preview-description span#text').text(excerpt);

            }, 4000);

            //Bind Clickables
            $('div#upfront-seo-preview h4').bind('click', function(event) {

                if ($('input#upfront-admin-meta-box-seo-title').val().length == 0)
                    $('input#upfront-admin-meta-box-seo-title').val($(this).text());

                $('input#upfront-admin-meta-box-seo-title')
                    .focus()
                    .css({ backgroundColor: '#FFF6BF' })
                    .animate({ backgroundColor: '#fff' }, 400);

                event.preventDefault();

            });

            $('div#upfront-seo-preview p#seo-preview-description').bind('click', function(event) {

                $('textarea#upfront-admin-meta-box-seo-description')
                    .focus()
                    .css({ backgroundColor: '#FFF6BF' })
                    .animate({ backgroundColor: '#fff' }, 400);

                event.preventDefault();

            });

        }
        /* End SEO Live Preview */

    });
})(jQuery);