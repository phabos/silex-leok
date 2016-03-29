/*******  App Logic  *******/
var App = {
  help: function() {
    console.log("help");
  },
  animateResponse: function(response) {
    jQuery('#message').html(response);
    $('#message').css({top: 80});
    setTimeout(function(){ $('#message').css({top: -200}); }, 3000);
    jQuery('.btn-primary i').removeClass('fa-spin');
  },
  animateBtn: function() {
    jQuery('.btn-primary i').addClass('fa-spin');
  }
};

/*******  Settings View  *******/
var SettingsView = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.settings = new Settings;
    this.render();
    this.xhr = false;
  },

  render: function(){
    var template = _.template( $("#settings_template").html() );
    var obj = this;

    // fetch settings && loading && pass to view
    this.settings.fetch({
      success: function (settings) {
        obj.$el.html( template( { settings: obj.settings.toJSON() } ) );
      }
    });

    //this.$el.html( template( this.settings.toJSON() ) );

  },

  events: {
    "click button.btn-primary": "saveSettings"
  },

  saveSettings: function( event ){
    var obj = this;
    App.animateBtn();
    if( this.xhr == true )
      return;

    this.xhr = true;

    this.$el.find("input[data-field]").each(function(){
      obj.settings.set(this.getAttribute("data-field"), this.value);
    });

    this.settings.save({},{
      success:function(model, response){
        App.animateResponse(response.msg);
        obj.xhr = false;
      },
      error:function(err){
        throw err;
      }
    });

  }

});

/*******  Article View  *******/
var ArtilcesView = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.articles = new Articles;
    this.render();
  },

  render: function(){
    var template = _.template( $("#articles_template").html() );
    var obj = this;

    this.articles.fetch({
      success: function (articles) {
        obj.$el.html( template( { articles: obj.articles.toJSON() } ) );
      }
    });
  },

});

/*******  Home View  *******/
var HomeView = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.render();
  },

  render: function(){
    // Compile the template using underscore
    var template = _.template( $("#home_template").html() );
    // Load the compiled HTML into the Backbone "el"
    this.$el.html( template );
  },

});
