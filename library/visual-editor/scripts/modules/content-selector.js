define(["jquery", "knockout", "underscore", "jqueryUI"], function($, ko, _) { showContentSelector = function() { $("div#content-selector-select").addClass("content-selector-visible");
        $("div#content-selector").css({ left: $("div#content-selector-select-content").offset().left });
        $(document).bind("mousedown", hideContentSelector);
        UpFront.iframe.contents().bind("mousedown", hideContentSelector); return $("div#content-selector-select"); };
    hideContentSelector = function(event) { if (event && ($(event.target).is("#content-selector-select") || $(event.target).parents("#content-selector-select").length === 1)) { return; }
        $("div#content-selector-select").removeClass("content-selector-visible");
        $(document).unbind("mousedown", hideContentSelector);
        UpFront.iframe.contents().unbind("mousedown", hideContentSelector); return $("div#content-selector-select"); };
    toggleContentSelector = function() { if ($("div#content-selector-select").hasClass("content-selector-visible")) { hideContentSelector(false); } else { showContentSelector(); } };
    switchToContent = function(selectedContent, showSwitchNotification, selectedContentName) { hideIframeOverlay(); return true; }; var contentSelector = { init: function() { contentSelector.setupViewModel();
            contentSelector.bind(); }, setupViewModel: function() { UpFront.viewModels.contentSelector = { pages: contentSelector.mapArrayToContentModel(UpFront.layouts.pages), search: ko.observableArray([]), searching: ko.observable(false) };
            $(document).ready(function() { ko.applyBindings(UpFront.viewModels.contentSelector, $("#content-selector-pages-container").get(0)); }); }, contentModel: function(content) { this.id = content.id;
            this.name = content.name;
            this.url = content.url;
            this.template = ko.observable(content.id);
            this.templateName = ko.observable(content.post_title);
            this.postStatus = ko.observable(content.id);
            this.customized = true;
            this.ajaxChildren = ko.observable(content.ajaxChildren);
            this.ajaxLoading = ko.observable(false);
            this.ajaxLoaded = ko.observable(false);
            this.ajaxShowMore = ko.observable(false);
            this.ajaxLoadOffset = ko.observable(0);
            this.children = contentSelector.mapArrayToContentModel(content.children); return this; }, mapArrayToContentModel: function(contents) { var normalizedData = [];
            $.each(contents, function(index, data) { normalizedData.push(new contentSelector.contentModel(data)); }); return ko.observableArray(normalizedData); }, loadContents: function(contentData, contentContext, $element, loadingMore) { var loadingMore = loadingMore || false; if (contentData.ajaxLoading()) { return false; }
            contentData.ajaxLoading(true); var $loadingIndicator = $('<li class="content-item content-loading-children"><span class="dashicons dashicons-update"></span> Loading...</li>');
            $loadingIndicator.insertAfter($element.parent()); return $.ajax(UpFront.ajaxURL, { type: "POST", async: true, data: { action: "upfront_visual_editor", method: "get_content_children", security: UpFront.security, content: contentData.id, offset: contentData.ajaxLoadOffset, mode: UpFront.mode }, success: function(data, textStatus) { $loadingIndicator.remove();
                    contentData.ajaxLoading(false); if (false && (!_.isArray(data) || !data.length) && !loadingMore) { contentContext.$data.ajaxChildren(false);
                        contentContext.$data.children([]); return $(self).removeClass("content-open"); } if (!_.isArray(contentContext.$data.children())) { contentContext.$data.children(ko.utils.unwrapObservable(contentSelector.mapArrayToContentModel(data))); } else { $.each(ko.utils.unwrapObservable(contentSelector.mapArrayToContentModel(data)), function(index, data) { contentContext.$data.children.push(data); }); }
                    contentContext.$data.ajaxLoaded(true);
                    contentContext.$data.ajaxLoadOffset(contentContext.$data.ajaxLoadOffset() + data.length); if (data.length == 30) { contentContext.$data.ajaxShowMore(true); } else { contentContext.$data.ajaxShowMore(false); } } }); }, searchContents: function(query) { UpFront.viewModels.contentSelector.searching(true); return $.ajax(UpFront.ajaxURL, { type: "POST", async: true, data: { action: "upfront_visual_editor", method: "query_posts", security: UpFront.security, query: query }, success: function(data, textStatus) { UpFront.viewModels.contentSelector.searching(false); if (!_.isArray(data) || !data.length) { return; } return UpFront.viewModels.contentSelector.search(ko.utils.unwrapObservable(contentSelector.mapArrayToContentModel(data))); } }); }, bind: function() { var contentSelectorEl = $("div#content-selector");
            $("div#content-selector-select-content").on("click", function() { toggleContentSelector(); return false; }); var contentSelectorSearchForm = $("#content-search-input-container form"); var contentSelectorSearchInput = contentSelectorSearchForm.find("input#content-search-input");
            contentSelectorSearchInput.on("search", function(event) { contentSelectorSearchForm.trigger("submit"); });
            contentSelectorSearchInput.on("keyup", function(event) { if ($(this).val().length === 0) { contentSelectorSearchForm.trigger("submit"); } }); var contentSelectorSearchFormSubmit = function(event) { var query = $("#content-search-input").val(); if (query.length === 0) { UpFront.viewModels.contentSelector.search([]);
                    event.preventDefault(); return false; }
                contentSelector.searchContents(query);
                event.preventDefault(); };
            $("#content-search-submit").on("click", contentSelectorSearchFormSubmit);
            contentSelectorSearchForm.on("submit", contentSelectorSearchFormSubmit);
            contentSelectorEl.delegate("span.show-this", "click", function(event) { if (typeof allowVECloseSwitch !== "undefined" && allowVECloseSwitch === false) { if (!confirm("You have unsaved changes, are you sure you want to switch contents?")) { return false; } }
                showIframeLoadingOverlay();
                switchToContent($(this).parent().data("content-id"));
                hideContentSelector();
                hideIframeOverlay();
                event.preventDefault(); return $(this).parents("span.content"); });
            contentSelectorEl.delegate("span.content", "click", function(event) { var self = this; var contentData = ko.dataFor(this); var contentContext = ko.contextFor(this); if (!$(this).parent().hasClass("has-children")) { return; }
                $(this).toggleClass("content-open"); if ($(this).parent().hasClass("has-ajax-children") && !contentContext.$data.ajaxLoaded()) { contentSelector.loadContents(contentData, contentContext, $(this)); } });
            contentSelectorEl.delegate("span.load-more-contents", "click", function(event) { var self = this; var contentData = ko.dataFor(this); var contentContext = ko.contextFor(this);
                $(self).text("Mehr laden...").attr("disabled", "disabled");
                $.when(contentSelector.loadContents(contentData, contentContext, $(this), true)).done(function() { $(self).text("Mehr laden...").attr("disabled", ""); }); }); } }; return contentSelector; });