(function($) { if (typeof UpFrontShrinkWrappers != "undefined") { $.each(UpFrontShrinkWrappers, function(selector, options) { $(selector).addClass("shrink"); });
        $(window).on("scroll", function() { $.each(UpFrontShrinkWrappers, function(selector, options) { var height = $(selector).attr("data-org-height"); if (typeof height == "undefined") { height = $(selector).height();
                    $(selector).attr("data-org-height", height); } var offset = $(selector).offset().top - $(window).scrollTop(); var ratio = options.shrink_ratio; var shrink_images = options.shrink_images; var shrink_elements = options.shrink_elements; var total = (height * (ratio / 100)); if (!$(selector).hasClass("is_stuck") || $(window).scrollTop() == 0) { $(selector).css("height", "");
                    $(selector).css("max-height", "");
                    $("#spacer-" + selector.replace("#", "")).css("height", ""); if (shrink_images) { $(selector).find("img").removeClass("is_shrinked");
                        $(selector).find("img").css("height", ""); } if (shrink_elements) { $(selector).find("a, p, li, span, h1, h2, h3, h4, h5, h6").removeClass("is_shrinked");
                        $(selector).find("nav").css("max-height", ""); }
                    $(selector).removeClass("is_shrinked"); return; } if ($("#wpadminbar").length > 0) { offset = offset - 32; } if ($(window).scrollTop() > 0) { $(selector).css("max-height", total);
                    $("#spacer-" + selector.replace("#", "")).css("height", total);
                    padding = parseFloat($(selector).css("padding-top").replace("px", ""));
                    padding = padding + parseFloat($(selector).css("padding-bottom").replace("px", "")); if (shrink_images) { $(selector).find("img").each(function() { var img_height = $(this).attr("data-org-imgheight"); if (typeof img_height == "undefined") { img_height = $(this).css("height").replace("px", "");
                                $(this).attr("data-org-imgheight", img_height); }
                            img_height = img_height - padding;
                            $(this).addClass("is_shrinked"); }); } if (shrink_elements) { $(selector).find("a, p, li, span, h1, h2, h3, h4, h5, h6").each(function() { $(this).addClass("is_shrinked"); });
                        $(selector).find("nav").each(function() { var nav_height = $(this).attr("data-org-navheight"); if (typeof nav_height == "undefined") { nav_height = $(this).css("height").replace("px", "");
                                $(this).attr("data-org-navheight", nav_height); }
                            nav_height = nav_height - padding;
                            $(this).css("max-height", (nav_height * (ratio / 100)) + "px");
                            $(this).addClass("is_shrinked"); }); }
                    $(selector).addClass("is_shrinked"); } else { $(selector).css("max-height", "");
                    $("#spacer-" + selector.replace("#", "")).css("height", ""); if (shrink_images) { $(selector).find("img").removeClass("is_shrinked"); } if (shrink_elements) { $(selector).find("nav").css("max-height", "");
                        $(selector).find("a, p, li, span, h1, h2, h3, h4, h5, h6").removeClass("is_shrinked");
                        $(selector).find("nav").removeClass("is_shrinked"); }
                    $(selector).removeClass("is_shrinked"); } }); }); } })(jQuery);