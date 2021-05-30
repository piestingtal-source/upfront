define(['jquery', 'knockout'], function($, ko) {

    var snapshots = {
        init: function() {

            snapshots.bind();
            snapshots.setupViewModel();

        },

        setupViewModel: function() {

            UpFront.viewModels.snapshots = {
                snapshots: ko.observableArray(UpFront.snapshots),
                formatSnapshotDatetime: function(datetime) {

                    var datetimeFrags = datetime.split(/[- :]/);

                    return new Date(Date.UTC(datetimeFrags[0], datetimeFrags[1] - 1, datetimeFrags[2], datetimeFrags[3], datetimeFrags[4], datetimeFrags[5])).toLocaleString();

                },
                rollbackToSnapshot: function(data, event) {

                    if (!confirm("Bist Du sicher, dass Du einen Rollback durchführen möchtest?\n\nDu verlierst alles zwischen diesem Schnappschuss und jetzt, es sei denn, Du speicherst einen anderen Schnappschuss."))
                        return false;

                    var button = $(event.target);

                    if (button.attr('disabled'))
                        return false;

                    /* Disable button temporarily */
                    button.attr('disabled', true);
                    button.addClass('button-depressed');
                    button.text('Wiederherstellen..');

                    /* Rollback */
                    $.post(UpFront.ajaxURL, {
                        security: UpFront.security,
                        action: 'upfront_visual_editor',
                        method: 'rollback_to_snapshot',
                        layout: UpFront.viewModels.layoutSelector.currentLayout(),
                        snapshot_id: data.id,
                        mode: UpFront.mode
                    }, function(response) {

                        if (typeof response.error != 'undefined')
                            return;

                        showNotification({
                            id: 'rolled-back-successfully',
                            message: 'Auf Schnappschuss zurückgesetzt.<br /><br /><strong>Aktualisieren des visuellen Editors in 3 Sekunden</strong>.',
                            success: true
                        });

                        button.text('Rolled Back!');

                        /* Reload the Visual Editor */
                        setTimeout(function() {
                            allowVEClose();
                            document.location.reload(true);
                        }, 1000);

                    });

                },
                deleteSnapshot: function(data, event) {

                    if (!confirm("Möchtest Du diesen Schnappschuss wirklich löschen?\n\nDu kannst dies nicht rückgängig machen oder einen anderen Schnappschuss wiederherstellen, um diesen Schnappschuss wiederherzustellen."))
                        return false;

                    var button = $(event.target);

                    if (button.hasClass('deletion-in-progress'))
                        return false;

                    /* Disable button temporarily */
                    button.addClass('deletion-in-progress');

                    /* Delete snapshot */
                    $.post(UpFront.ajaxURL, {
                        security: UpFront.security,
                        action: 'upfront_visual_editor',
                        method: 'delete_snapshot',
                        layout: UpFront.viewModels.layoutSelector.currentLayout(),
                        snapshot_id: data.id,
                        mode: UpFront.mode
                    }, function(response) {

                        if (typeof response.error != 'undefined')
                            return;

                        showNotification({
                            id: 'deleted-snapshot-successfully',
                            message: 'Schnappschuss erfolgreich gelöscht.',
                            success: true
                        });

                        UpFront.viewModels.snapshots.snapshots.remove(data);

                    });


                },
                saveSnapshot: function(data, event) {

                    var button = $(event.target);

                    if (button.attr('disabled'))
                        return false;

                    /* Disable button temporarily */
                    button.attr('disabled', true);
                    button.text('Snapshot speichern...');

                    /* Add the snapshot */
                    button.siblings('.spinner').show();

                    /* Prompt for comments about snapshot */
                    var snapshotComments = prompt("(Optional)\n\nGib den Namen oder die Beschreibung der Änderungen in diesem Schnappschuss ein.");

                    $.post(UpFront.ajaxURL, {
                        security: UpFront.security,
                        action: 'upfront_visual_editor',
                        method: 'save_snapshot',
                        layout: UpFront.viewModels.layoutSelector.currentLayout(),
                        mode: UpFront.mode,
                        snapshot_comments: snapshotComments
                    }, function(response) {

                        if (typeof response.timestamp == 'undefined')
                            return;

                        showNotification({
                            id: 'snapshot-saved',
                            message: 'Schnappschuss gespeichert.',
                            success: true
                        });

                        UpFront.viewModels.snapshots.snapshots.unshift({
                            id: response.id,
                            timestamp: response.timestamp,
                            comments: response.comments
                        });

                        button.text('Schnappschuss speichern');
                        button.removeAttr('disabled');
                        button.siblings('.spinner').hide();

                    });

                }
            }

            $(document).ready(function() {
                ko.applyBindings(UpFront.viewModels.snapshots, $('#box-snapshots').get(0));
            });

        },

        bind: function() {


        }
    }


    return snapshots;

});