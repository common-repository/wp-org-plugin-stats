(function($) {

    // Notice Hide
    $('body').on('click', '.jltwporgst-upgrade-popup .popup-dismiss', function(evt) {
        evt.preventDefault();
        $(this).closest('.jltwporgst-upgrade-popup').fadeOut(200);
    });

    // Notice Show
    $('body').on('click', '.disabled', function(evt) {
        evt.preventDefault();
        $('.jltwporgst-upgrade-popup').fadeIn(200);
    });

    // Recommended Plugins

    // Install
    $('body').on('click', '.plugin-action-buttons .install-now', function(e) {
        e.preventDefault();
        if (!$(this).hasClass("updating-message")) {
            let plugin = $(this).attr("data-install-url");
            installPlugin($(this), plugin);
        }
    });

    // Active
    $('body').on('click', '.plugin-action-buttons .activate-now', function() {
        let file = $(this).attr("data-plugin-file");
        activatePlugin($(this), file);
    });

    // Update
    $('body').on('click', '.plugin-action-buttons .update-now', function() {
        if (!$(this).hasClass("updating-message")) {
            const plugin = $(this).attr("data-plugin");
            updatePlugin($(this), plugin);
        }
    });

    // Tab
    $('.filter-links').on('click', 'a', function(e) {
        e.preventDefault();
        let cls = $(this).data('type');
        $(this).addClass('current').parent().siblings().find('a').removeClass('current');
        $('#the-list .plugin-card').each(function(i, el) {
            if (cls == 'all') {
                $(this).removeClass('hide');
            } else {
                if ($(this).hasClass(cls)) {
                    $(this).removeClass('hide');
                } else {
                    $(this).addClass('hide');
                }
            }
        });
    });

    // Search
    $('.jltwporgst-search-plugins #search-plugins').on('keyup', function() {
        var value = $(this).val();
        var srch = new RegExp(value, "i");
        $('#the-list .plugin-card').each(function() {
            var $this = $(this);
            if (!($this.find('.name h3 a, .desc p').text().search(srch) >= 0)) {
                $this.addClass('hide');
            }
            if (($this.find('.name h3 a, .desc p').text().search(srch) >= 0)) {
                $this.removeClass('hide');
            }
        });
    });



    tinymce.PluginManager.add('jltwpost_button', function( editor, url ) {
        editor.addButton( 'jltwpost_button', {
            title: 'WP.Org Plugin Stats Button',
            type: 'menubutton',
            icon: 'faq-icon',
            menu: [
                {
                    text: 'Shortcode',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'WP.Org Plugin Stats',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'slug',
                                    label: 'Plugin Slug'
                                },
                                {
                                    type: 'textbox',
                                    name: 'field',
                                    value: 'downloaded',
                                    label: 'Data Field'
                                }
                            ],

                            onsubmit: function( e ) {
                                editor.insertContent( '[wpops slug="' + e.data.slug + '" field="' + e.data.field + '" ]');
                            }
                        });
                    }
                }
           ]
        });
    });

})(jQuery);




function activatePlugin(element, file) {
    element.addClass("button-disabled");
    element.attr("disabled", "disabled");
    element.text("Processing...");
    jQuery.ajax({
        url: JLTWPORGSTCORE.admin_ajax,
        type: "POST",
        data: {
            action: "jltwporgst_recommended_activate_plugin",
            file: file,
            nonce: JLTWPORGSTCORE.recommended_nonce
        },
        success: function(response) {
            if (response.success === true) {
                const pluginStatus = jQuery(".plugin-status .plugin-status-inactive[data-plugin-file='" + file + "']")
                pluginStatus.text("Active");
                pluginStatus.addClass("plugin-status-active");
                pluginStatus.removeClass("plugin-status-inactive");
                element.removeClass("active-now");
                element.text("Activated");
            } else {
                element.removeClass("button-disabled");
                element.prop("disabled", false);
                element.text("Activated");
            }
        },
    })
}

function installPlugin(element, plugin) {
    element.removeClass("button-primary");
    element.addClass("updating-message");
    element.text("Installing...");
    jQuery.ajax({
        url: JLTWPORGSTCORE.admin_ajax,
        type: "POST",
        data: {
            action: "jltwporgst_recommended_upgrade_plugin",
            type: 'install',
            plugin: plugin,
            nonce: JLTWPORGSTCORE.recommended_nonce
        },
        success: function(response) {
            if (response.success === true) {
                element.removeClass("updating-message");
                element.addClass("updated-message installed button-disabled");
                element.attr("disabled", "disabled");
                element.removeAttr("data-install-url");
                element.text("Installed!");
                setTimeout(() => {
                    const pluginStatus = jQuery(".plugin-status .plugin-status-not-install[data-plugin-url='" + plugin + "']")
                    pluginStatus.text("Active");
                    pluginStatus.addClass("plugin-status-active");
                    pluginStatus.removeClass("plugin-status-not-install");
                    pluginStatus.removeAttr("data-install-url");
                    element.removeClass("install-now updated-message installed");
                    element.text("Activated");
                    element.removeAttr("aria-label");
                }, 500);

            } else {
                element.removeClass("updating-message");
                element.addClass("button-primary");
                element.text("Install Now");
            }
        },
    })
}

function updatePlugin(element, plugin) {
    element.addClass("updating-message");
    element.text("Updating...");
    jQuery.ajax({
        url: JLTWPORGSTCORE.admin_ajax,
        type: "POST",
        data: {
            action: "jltwporgst_recommended_upgrade_plugin",
            type: "update",
            plugin: plugin,
            nonce: JLTWPORGSTCORE.recommended_nonce
        },
        success: function(response) {
            if (response.success === true) {
                element.removeClass("updating-message");
                element.addClass("updated-message button-disabled");
                element.attr("disabled", "disabled");
                element.text("Updated!");
                if (response.data.active === false) {
                    const pluginStatus = jQuery(".plugin-status .plugin-status-inactive[data-plugin-file='" + plugin + "']");
                    pluginStatus.text("Active");
                    pluginStatus.addClass("plugin-status-active");
                    pluginStatus.removeClass("plugin-status-inactive");
                    pluginStatus.removeAttr("data-plugin-file");
                }
            } else {
                element.removeClass("updating-message");
                element.text("Update Now");
            }
        },
    })
}