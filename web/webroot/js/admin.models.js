var Settings = Backbone.Model.extend({
  urlRoot: '/admin/settings',

  defaults: {
    'email': '',
    'title': '',
    'slogan': ''
  },

  initialize: function() {
    console.log('Here init settings model');
  },

});

var Article = Backbone.Model.extend({
  urlRoot: '/admin/article',

  defaults: {
    'id': '',
    'title': '',
    'content': '',
    'excerpt': '',
    'gallery': '',
    'tags': ''
  },

  initialize: function() {
    console.log('Here init article model');
  },

});

var Articles = Backbone.Collection.extend({
  url: '/admin/articles',
  model: Article
});