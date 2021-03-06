jQuery(function($) {

    var templates = {
        init: function() {

            templates.bind();
            templates.setupViewModel();

        },

        setupViewModel: function() {

            UpFront.viewModels.templates = {
                templates: ko.observableArray(UpFront.templates),
                active: ko.observable(UpFront.templateActive),
                activateSkin: function() {

                    var skin = this;

                    /* Don't try to activate if it's already activated */
                    if (skin.id == UpFront.viewModels.templates.active().id)
                        return;

                    /* Send AJAX Request to switch skins */
                    $.post(UpFront.ajaxURL, {
                        security: UpFront.security,
                        action: 'upfront_visual_editor',
                        method: 'switch_skin',
                        skin: skin.id
                    }, function(response) {

                        /* Set this skin as the activated skin */
                        UpFront.viewModels.templates.active(skin);

                        showNotification({
                            id: 'skin-switched',
                            message: skin.name + ' activated.',
                            closeTimer: 5000,
                            success: true
                        });

                    });

                },
                deleteSkin: function() {

                    var skin = this;

                    if (!confirm('Möchtest Du diese Vorlage wirklich löschen? Alle Designeinstellungen, Blöcke und Layouteinstellungen für diese Vorlage werden gelöscht.'))
                        return;

                    /* Send AJAX Request to switch skins */
                    $.post(UpFront.ajaxURL, {
                        security: UpFront.security,
                        action: 'upfront_visual_editor',
                        method: 'delete_skin',
                        skin: skin.id
                    }, function(response) {

                        if (response != 'success') {

                            return showErrorNotification({
                                id: 'unable-to-delete-skin',
                                message: 'Vorlage kann nicht gelöscht werden.',
                            });

                        } else {

                            showNotification({
                                id: 'skin-deleted',
                                message: skin.name + ' deleted.',
                                closeTimer: 5000,
                                success: true
                            });

                        }

                        UpFront.viewModels.templates.templates.remove(skin);

                    });

                }
            }

            ko.applyBindings(UpFront.viewModels.templates, $('.upfront-templates').get(0));

        },

        bind: function() {

            /* Skin Upload button */
            $('#install-template').on('click', function() {

                if ($(this).is('[disabled]'))
                    return;

                $('#upload-skin input[type="file"]').first().trigger('click');

            });


            /*		Install upfront services cloud template		*/
            $('.install-cloud-template').on('click', function() {

                var id = this.id.split('-')[1];
                var token = $(this).data('token');

                $.post(UpFront.apiURL + 'template/get-data', {
                    token: token,
                    id: id,
                    user_agent: 'upfront',
                }, function(response) {

                    try {
                        var skin = JSON.parse(response.skin);

                        if (skin && typeof skin.name != 'undefined' && typeof skin['data-type'] != 'undefined') {

                            /* Check to be sure that the JSON file is a layout */
                            if (skin['data-type'] != 'skin') {
                                return alert('Vorlage kann nicht geladen werden. Bitte stelle sicher, dass die Datei eine gültige UpFront-Vorlage ist.');
                            }

                            /* Deactivate install template button */
                            $('#install-template').attr('disabled', 'true');

                            showNotification({
                                id: 'installing-skin',
                                message: 'Installing Template: ' + skin['name'],
                                closeTimer: false,
                                closable: false
                            });

                            UpFront.viewModels.templates.templates.push({
                                description: null,
                                name: 'Installing ' + skin['name'] + '...',
                                installing: true,
                                id: null,
                                author: null,
                                active: false,
                                version: null
                            });

                            installSkin(skin);

                        }

                    } catch (e) {

                        return alert('Vorlage kann nicht geladen werden. Bitte stelle sicher, dass die Datei eine gültige UpFront-Vorlage ist.');

                    }

                });

            });

            $('#upload-skin input[type="file"]').on('change', function(event) {

                var skinFile = $(this).get(0).files[0];

                if (skinFile && typeof skinFile.name != 'undefined' && typeof skinFile.type != 'undefined') {

                    var skinReader = new FileReader();

                    skinReader.onload = function(e) {

                        var skinJSON = e.target.result;

                        try {

                            var skin = JSON.parse(skinJSON);

                            /* Check to be sure that the JSON file is a layout */
                            if (skin['data-type'] != 'skin')
                                return alert('Vorlage kann nicht geladen werden. Bitte stelle sicher, dass die Datei eine gültige UpFront-Vorlage ist.');

                            /* Deactivate install template button */
                            $('#install-template').attr('disabled', 'true');

                            showNotification({
                                id: 'installing-skin',
                                message: 'Vorlage installieren: ' + skin['name'],
                                closeTimer: false,
                                closable: false
                            });

                            UpFront.viewModels.templates.templates.push({
                                description: null,
                                name: 'Installing ' + skin['name'] + '...',
                                installing: true,
                                id: null,
                                author: null,
                                active: false,
                                version: null
                            });

                            installSkin(skin);

                        } catch (e) {

                            return alert('Vorlage kann nicht geladen werden. Bitte stelle sicher, dass die Datei eine gültige UpFront-Vorlage ist.');

                        }

                    }

                    $('#upload-skin input[type="file"]').val('');

                    skinReader.readAsText(skinFile);

                } else {

                    alert('Vorlage kann nicht geladen werden. Bitte stelle sicher, dass die Datei eine gültige UpFront-Vorlage ist.');

                }

            });


            installSkin = function(skin) {


                if (typeof skin['image-definitions'] == 'object' && Object.keys(skin['image-definitions']).length) {

                    var numberOfImages = Object.keys(skin['image-definitions']).length;
                    var importedImages = {};

                    showNotification({
                        id: 'skin-importing-images',
                        message: 'Bilder importieren...',
                        closeTimer: false,
                        closable: false
                    });

                    var importSkinImage = function(imageID) {

                        /* Update notification for image import */
                        var imageIDInt = parseInt(imageID.replace('%%', '').replace('IMAGE_REPLACEMENT_', ''));

                        updateNotification('skin-importing-images', 'Importiere Bild (' + imageIDInt + '/' + numberOfImages + ')');

                        /* Do the AJAX request to upload the image */
                        var imageImportXhr = $.post(UpFront.ajaxURL, {
                                security: UpFront.security,
                                action: 'upfront_visual_editor',
                                method: 'import_image',
                                imageID: imageID,
                                imageContents: skin['image-definitions'][imageID]
                            }, null, 'json')
                            .always(function(response) {

                                /* Update notification */

                                /* Check if error.  If so, fire notification */
                                if (typeof response['url'] == 'undefined') {
                                    var response = 'ERROR';

                                    showNotification({
                                        id: 'skin-importing-images-error-' + imageIDInt,
                                        message: 'Fehler beim Importieren des Bildes #' + imageIDInt,
                                        closeTimer: 10000,
                                        closable: true,
                                        error: true
                                    });
                                }

                                /* Store uploaded image URL */
                                importedImages[imageID] = response;

                                /* Check if there are more images to upload.  If so, upload them. */
                                var nextImageID = '%%IMAGE_REPLACEMENT_' + (parseInt(imageID.replace('%%', '').replace('IMAGE_REPLACEMENT_', '')) + 1) + '%%';

                                if (typeof skin['image-definitions'][nextImageID] != 'undefined') {

                                    importSkinImage(nextImageID);

                                    /* If not, finalize skin installation */
                                } else {

                                    /* Hide notification since images are uploaded is complete */
                                    hideNotification('skin-importing-images');

                                    /* Finalize */
                                    skin['imported-images'] = importedImages;

                                    finalizeSkinInstallation(skin);

                                }

                            });
                        /* End doing AJAX request to upload image */

                    }

                    importSkinImage('%%IMAGE_REPLACEMENT_1%%');

                } else {

                    finalizeSkinInstallation(skin);

                }

            }


            finalizeSkinInstallation = function(skin) {

                /* Remove image definitions from skin array since they've already been imported */
                if (typeof skin['image-definitions'] != 'undefined')
                    delete skin['image-definitions'];

                /* Do AJAX request to install skin */
                return $.post(UpFront.ajaxURL, {
                    security: UpFront.security,
                    action: 'upfront_visual_editor',
                    method: 'install_skin',
                    skin: JSON.stringify(skin)
                }).done(function(data) {

                    var skin = data;

                    if (typeof skin['error'] !== 'undefined' || typeof skin['name'] == 'undefined') {

                        if (typeof skin['error'] == 'undefined')
                            skin['error'] = 'Vorlage konnte nicht installiert werden.';

                        UpFront.viewModels.templates.templates.pop();
                        $('#install-template').removeAttr('disabled');

                        return showNotification({
                            id: 'skin-not-installed',
                            message: 'Fehler: ' + skin['error'],
                            closable: true,
                            closeTimer: false,
                            error: true
                        });

                    }

                    hideNotification('installing-skin');

                    showNotification({
                        id: 'skin-installed',
                        message: skin['name'] + ' erfolgreich installiert.',
                        closeTimer: 5000,
                        success: true
                    });

                    /* Pop off the last skin which is going to be the loader */
                    UpFront.viewModels.templates.templates.pop();
                    UpFront.viewModels.templates.templates.push($.extend({}, { description: null }, skin));

                    /* Reactive install template button */
                    $('#install-template').removeAttr('disabled');

                }).fail(function(data) {

                    showNotification({
                        id: 'skin-not-installed',
                        message: 'Fehler: Vorlage konnte nicht installiert werden.',
                        closable: true,
                        closeTimer: false,
                        error: true
                    });

                });

            }

            /* Skin Export */
            $('#export-template-submit').on('click', function(event) {

                event.preventDefault();

                var params = {
                    'security': UpFront.security,
                    'action': 'upfront_visual_editor',
                    'method': 'export_skin',
                    'skin-info': $('#export-template-form').serialize()
                }


                var exportURL = UpFront.ajaxURL + '?' + $.param(params);
                return window.open(exportURL);


            });

            /* Export Template Image */
            var BTTemplateExportImageFrame;

            $('#template-export-image-button').on('click', function(event) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (BTTemplateExportImageFrame) {
                    BTTemplateExportImageFrame.open();
                    return;
                }

                // Create the media frame.
                BTTemplateExportImageFrame = wp.media.frames.file_frame = wp.media({
                    title: 'Bild auswählen für Vorlage',
                    button: {
                        text: 'Bild auswählen',
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                BTTemplateExportImageFrame.on('select', function() {
                    attachment = BTTemplateExportImageFrame.state().get('selection').first().toJSON();

                    $('input#template-export-image').val(attachment.url);

                    $('img#template-export-image-preview')
                        .attr('src', attachment.url)
                        .show();

                });

                BTTemplateExportImageFrame.open();
            });



            /* Save on cloud Template Image */
            var BTTemplateExportImageFrame;

            $('#template-save-on-cloud-image-button ').on('click', function(event) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (BTTemplateExportImageFrame) {
                    BTTemplateExportImageFrame.open();
                    return;
                }

                // Create the media frame.
                BTTemplateExportImageFrame = wp.media.frames.file_frame = wp.media({
                    title: 'Wähle Bild für Vorlage',
                    button: {
                        text: 'Bild auswählen',
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                BTTemplateExportImageFrame.on('select', function() {
                    attachment = BTTemplateExportImageFrame.state().get('selection').first().toJSON();

                    $('input#template-save-on-cloud-image').val(attachment.url);

                    $('img#template-save-on-cloud-image-preview')
                        .attr('src', attachment.url)
                        .show();

                });

                BTTemplateExportImageFrame.open();
            });


            /* Add Blank Skin */
            $('#add-blank-template').on('click', function() {

                var skinName = window.prompt('Bitte gib einen Namen für die neue Vorlage ein:', 'Template Name');

                if (!skinName || $('#notification-adding-blank-skin').length)
                    return;

                /* Perform AJAX request to create the skin and get the ID and name */
                $.post(UpFront.ajaxURL, {
                    security: UpFront.security,
                    action: 'upfront_visual_editor',
                    method: 'add_blank_skin',
                    skinName: skinName
                }, function(response) {

                    var skinID = response['id'];
                    var skinName = response['name'];

                    showNotification({
                        id: 'added-blank-skin',
                        message: skinName + ' erfolgreich hinzugefügt.',
                        closeTimer: 5000,
                        success: true
                    });

                    UpFront.viewModels.templates.templates.push({
                        id: skinID,
                        name: skinName,
                        version: null,
                        author: null,
                        description: null
                    });

                }, 'json');

            });

        }
    }

    templates.init();

});