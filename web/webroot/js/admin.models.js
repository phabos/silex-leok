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
  url: '/admin/article/',

  defaults: {
    'id': '',
    'title': '',
    'content': '',
    'excerpt': '',
    'gallery': '',
    'tags': ''
  },

  initialize: function(options) {
    this.url = this.url + options.id;
    console.log('Here init article model ' + this.url);
  },

});

var Articles = Backbone.Collection.extend({
  url: '/admin/articles',
  model: Article
});