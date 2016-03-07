var AppRouter = Backbone.Router.extend({
    routes: {
        "settings": "settingsRoute",
        "articles": "articlesRoute",
        "articleedit": "articleEditRoute",
        "*path": "defaultRoute"
        // matches http://example.com/#anything-here
    }
});
// Initiate the router
var app_router = new AppRouter;

app_router.on('route:defaultRoute', function(actions) {
    // Display last articles published
    console.log("default route");
    var home_view = new HomeView;
});

app_router.on('route:settingsRoute', function(actions) {
    console.log("display settings");
    var settings_view = new SettingsView;
});

app_router.on('route:articlesRoute', function(actions) {
    console.log("Display articles");
    var articles_view = new ArtilcesView;
});

app_router.on('route:articleEditRoute', function(actions) {
    console.log("Display articles edit route");
});

// Start Backbone history a necessary step for bookmarkable URL's
Backbone.history.start();
