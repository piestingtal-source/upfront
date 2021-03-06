/*! Pushy - v1.3.0 - 2019-6-25
 * Pushy is a responsive off-canvas navigation menu using CSS transforms & transitions.
 * https://github.com/christophery/pushy/
 * by Christopher Yee */
(function($) { window.UpFrontPushy = function() { var pushy = $(".pushy"),
            body = $("body"),
            container = $("#whitewrap"),
            push = $(".push"),
            pushyLeft = "pushy-left",
            pushyOpenLeft = "pushy-open-left",
            pushyOpenRight = "pushy-open-right",
            siteOverlay = $(".pushy-site-overlay"),
            menuBtn = $(".pushy-menu-toggle"),
            menuBtnFocus = $(".menu-btn"),
            menuLinkFocus = $(pushy.data("focus")),
            menuSpeed = 200,
            menuWidth = pushy.width() + "px",
            submenuClass = ".pushy-submenu",
            submenuOpenClass = "pushy-submenu-open",
            submenuClosedClass = "pushy-submenu-closed",
            submenu = $(submenuClass);
        $(document).keyup(function(e) { if (e.keyCode == 27) { if (body.hasClass(pushyOpenLeft) || body.hasClass(pushyOpenRight)) { if (cssTransforms3d) { closePushy(); } else { closePushyFallback();
                        opened = false; } if (menuBtnFocus) { menuBtnFocus.focus(); } } } });

        function togglePushy() { if (pushy.hasClass(pushyLeft)) { body.toggleClass(pushyOpenLeft); } else { body.toggleClass(pushyOpenRight); } if (menuLinkFocus) { pushy.one("transitionend", function() { menuLinkFocus.focus(); }); } }

        function closePushy() { if (pushy.hasClass(pushyLeft)) { body.removeClass(pushyOpenLeft); } else { body.removeClass(pushyOpenRight); } }

        function openPushyFallback() { if (pushy.hasClass(pushyLeft)) { body.addClass(pushyOpenLeft);
                pushy.animate({ left: "0px" }, menuSpeed);
                container.animate({ left: menuWidth }, menuSpeed);
                push.animate({ left: menuWidth }, menuSpeed); } else { body.addClass(pushyOpenRight);
                pushy.animate({ right: "0px" }, menuSpeed);
                container.animate({ right: menuWidth }, menuSpeed);
                push.animate({ right: menuWidth }, menuSpeed); } if (menuLinkFocus) { menuLinkFocus.focus(); } }

        function closePushyFallback() { if (pushy.hasClass(pushyLeft)) { body.removeClass(pushyOpenLeft);
                pushy.animate({ left: "-" + menuWidth }, menuSpeed);
                container.animate({ left: "0px" }, menuSpeed);
                push.animate({ left: "0px" }, menuSpeed); } else { body.removeClass(pushyOpenRight);
                pushy.animate({ right: "-" + menuWidth }, menuSpeed);
                container.animate({ right: "0px" }, menuSpeed);
                push.animate({ right: "0px" }, menuSpeed); } }

        function toggleSubmenu() { $(submenuClass).addClass(submenuClosedClass);
            $(submenuClass).on("click", function(e) { var selected = $(this); if (selected.hasClass(submenuClosedClass)) { selected.siblings(submenuClass).addClass(submenuClosedClass).removeClass(submenuOpenClass);
                    selected.removeClass(submenuClosedClass).addClass(submenuOpenClass); } else { selected.addClass(submenuClosedClass).removeClass(submenuOpenClass); }
                e.stopPropagation(); }); }

        function toggleSubmenuFallback() { $(submenuClass).addClass(submenuClosedClass);
            submenu.children("a").on("click", function(event) { event.preventDefault();
                $(this).toggleClass(submenuOpenClass).next(".pushy-submenu ul").slideToggle(200).end().parent(submenuClass).siblings(submenuClass).children("a").removeClass(submenuOpenClass).next(".pushy-submenu ul").slideUp(200); }); } var cssTransforms3d = (function csstransforms3d() { var el = document.createElement("p"),
                supported = false,
                transforms = { webkitTransform: "-webkit-transform", OTransform: "-o-transform", msTransform: "-ms-transform", MozTransform: "-moz-transform", transform: "transform" }; if (document.body !== null) { document.body.insertBefore(el, null); for (var t in transforms) { if (el.style[t] !== undefined) { el.style[t] = "translate3d(1px,1px,1px)";
                        supported = window.getComputedStyle(el).getPropertyValue(transforms[t]); } }
                document.body.removeChild(el); return (supported !== undefined && supported.length > 0 && supported !== "none"); } else { return false; } })(); if (cssTransforms3d) { toggleSubmenu();
            menuBtn.on("click", function() { togglePushy(); });
            siteOverlay.on("click", function() { togglePushy(); }); } else { body.addClass("no-csstransforms3d"); if (pushy.hasClass(pushyLeft)) { pushy.css({ left: "-" + menuWidth }); } else { pushy.css({ right: "-" + menuWidth }); }
            container.css({ "overflow-x": "hidden" }); var opened = false;
            toggleSubmenu();
            menuBtn.on("click", function() { if (opened) { closePushyFallback();
                    opened = false; } else { openPushyFallback();
                    opened = true; } });
            siteOverlay.on("click", function() { if (opened) { closePushyFallback();
                    opened = false; } else { openPushyFallback();
                    opened = true; } }); } }; }(jQuery));