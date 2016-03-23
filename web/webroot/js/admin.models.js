var Settings = Backbone.Model.extend({
  urlRoot: "/admin/settings",

  defaults: {
    'email': '',
    'title': '',
    'slogan': ''
  },

  initialize: function() {
    console.log('Here init settings model');
  },

});
