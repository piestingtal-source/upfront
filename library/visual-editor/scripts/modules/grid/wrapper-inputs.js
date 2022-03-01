/* WRAPPER OPTION INPUT CALLBACKS */
/* Grid Settings */
wrapperOptionCallbackIndependentGrid = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    /* Find wrapper and UpFront Grid UI widget */
    var wrapperGridObject = wrapper.data('ui-upfrontGrid');

    /* Update wrapper object and the guides */
    wrapperGridObject.options.useIndependentGrid = value;

    /* Finalize: Update the Container/Grid CSS and reset draggable/resizable, etc */
    wrapperGridObject.updateGridCSS();

}


wrapperOptionCallbackColumnCount = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    /* Throw error saying column count can't be changed if there are blocks in the grid */
    if (wrapper.find('.block:visible').length) {

        showNotification({
            id: 'wrapper-column-count-change-error-',
            message: "Dieser Container muss leer sein, bevor Du die Anzahl der Spalten ändern kannst.\n\nZiehe die Blöcke entweder auf einen anderen Container oder lösche sie, wenn sie nicht mehr benötigt werden.",
            closeTimer: 10000,
            closable: true,
            error: true
        });

        return false;

    } else {
        /* Find wrapper and UpFront Grid UI widget */
        var wrapperGridObject = wrapper.data('ui-upfrontGrid');

        /* Update wrapper object and the guides */
        wrapperGridObject.options.columns = value;

        wrapperGridObject.addColumnGuides();

        /* Finalize: Update the Container/Grid CSS and reset draggable/resizable, etc */
        wrapperGridObject.updateGridCSS();
    }

}


wrapperOptionCallbackColumnWidth = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    /* Find wrapper and UpFront Grid UI widget */
    var wrapperGridObject = wrapper.data('ui-upfrontGrid');

    /* Update wrapper object and the guides */
    wrapperGridObject.options.columnWidth = value;

    /* Finalize: Update the Container/Grid CSS and reset draggable/resizable, etc */
    wrapperGridObject.updateGridCSS();

}


wrapperOptionCallbackGutterWidth = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    /* Find wrapper and UpFront Grid UI widget */
    var wrapperGridObject = wrapper.data('ui-upfrontGrid');

    /* Update wrapper object and the guides */
    wrapperGridObject.options.gutterWidth = value;

    /* Finalize: Update the Container/Grid CSS and reset draggable/resizable, etc */
    wrapperGridObject.updateGridCSS();
}


/* Container Margin Inputs */
wrapperOptionCallbackMarginTop = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    wrapper.css({ marginTop: value });

    /* Visible feedback of margin */
    wrapperMarginFeedbackCreator(wrapper, 'top');

}

wrapperOptionCallbackMarginBottom = function(input, value) {

    var wrapperID = input.parents("[data-panel-args]").data("panel-args").wrapper.id.replace('wrapper-', '');
    var wrapper = $i('.wrapper[data-id="' + wrapperID + '"]');

    if (typeof wrapper == 'undefined' || !wrapper.length)
        return false;

    wrapper.css({
        marginBottom: value
    });

    /* Visible feedback of margin */
    wrapperMarginFeedbackCreator(wrapper, 'bottom');

}

wrapperMarginFeedbackCreator = function(wrapper, topOrBottom) {

    /* Remove any existing margin feedback element */
    if (wrapper.find('.wrapper-margin-feedback').length) {

        clearTimeout(wrapper.find('.wrapper-margin-feedback').data('fadeout-timeout'));
        wrapper.find('.wrapper-margin-feedback').remove();

    }

    /* Create margin feedback element */
    var wrapperMarginFeedback = $('<div class="wrapper-margin-feedback"></div>').prependTo(wrapper);

    /* Style it */
    var value = parseInt(wrapper.css('margin' + topOrBottom.capitalize()).replace('px', ''));

    var feedbackCSS = {
        position: 'absolute',
        width: wrapper.outerWidth(),
        left: 0,
        height: value,
        backgroundColor: 'rgba(255, 127, 0, .35)'
    };

    /* Determine where feedback helper will go based on topOrBottom (whether it's marginTop or marginBottom) */
    feedbackCSS[topOrBottom] = '-' + value + 'px';

    /* Send CSS to margin feedback helper */
    wrapperMarginFeedback.css(feedbackCSS);

    /* Set a timer to fade it out and remove it */
    wrapperMarginFeedback.data('fadeout-timeout', setTimeout(function() {

        wrapperMarginFeedback.fadeOut(200);

    }, 400));

    return wrapperMarginFeedback;

}

/* Default Container Options */
updateGridWidthInput = function(context) {

        var columns = $(context).find('input[name="columns"]').val();
        var columnWidth = $(context).find('input[name="column-width"]').val();
        var gutterWidth = $(context).find('input[name="gutter-width"]').val();

        var gridWidth = (columnWidth * columns) + ((columns - 1) * gutterWidth);

        return $(context).find('input[name="grid-width"]').val(gridWidth);

    }
    /* END WRAPPER OPTION INPUT CALLBACKS */