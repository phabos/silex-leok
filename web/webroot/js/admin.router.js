var AppRouter = Backbone.Router.extend({
    routes: {
        "settings": "settingsRoute",
        "articles": "articlesRoute",
        "articleedit/:id": "articleEditRoute",
        "*path": "defaultRoute"
        // matches http://example.com/#anything-here
    }
});
// Initiate the router
var app_router = new AppRouter;

app_router.on('route:defaultRoute', function() {
    // Display last articles published
    console.log("default route");
    var home_view = new HomeView;
});

app_router.on('route:settingsRoute', function() {
    console.log("display settings");
    var settings_view = new SettingsView;
});

app_router.on('route:articlesRoute', function() {
    console.log("Display articles list");
    var articles_list = new ArtilcesList;
});

app_router.on('route:articleEditRoute', function(id) {
    console.log("Display articles edit route / " + id);
    var articles_edit = new ArtilcesEdit({ id: id });
});

// Start Backbone history a necessary step for bookmarkable URL's
Backbone.history.start();
