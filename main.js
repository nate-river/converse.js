require(["jquery", "converse"], function($, converse) {
    // Most of these initialization values are the defaults but they're
    // included here as a reference.
    converse.initialize({
        auto_list_rooms: false,
        auto_subscribe: false,
        bosh_service_url: 'http://localhost/http-bind', // Please use this connection manager only for testing purposes
        hide_muc_server: false,
        prebind: false,
        show_controlbox_by_default: true,
        xhr_user_search: false
    });
});
