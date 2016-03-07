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
    "click input[type=button]": "doSearch"
  },

  doSearch: function( event ){
    // Button clicked, you can access the element that was clicked with event.currentTarget
    alert( "Search for " + $("#search_input").val() );
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
