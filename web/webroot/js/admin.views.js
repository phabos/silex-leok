var SettingsView = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.render();
  },

  render: function(){
    //Pass variables in using Underscore.js Template
    //var variables = { search_label: "My Search" };
    // Compile the template using underscore
    var template = _.template( $("#settings_template").html() );
    // Load the compiled HTML into the Backbone "el"
    this.$el.html( template );
  },

  events: {
    "click button.btn-primary": "saveSettings"
  },

  saveSettings: function( event ){
    var settings = new Settings();
    var view = this;

    this.$el.find("input[data-field]").each(function(){
      settings.set(this.getAttribute("data-field"), this.value);
    });

    settings.save({},{
      success:function(){
        console.log('we re good');
      },
      error:function(err){
        throw err;
      }
    });

  }

});

var ArtilcesView = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.render();
  },

  render: function(){
    // Compile the template using underscore
    var template = _.template( $("#articles_template").html() );
    // Load the compiled HTML into the Backbone "el"
    this.$el.html( template );
  },

});

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
