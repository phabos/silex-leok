var Settings = Backbone.Model.extend({
  urlRoot: '/admin/settings',

  initialize: function() {
    console.log('Here init settings model');
  },

});

var Article = Backbone.Model.extend({
  url: '/admin/article/',

  initialize: function(options) {
    this.url = this.url + options.id;
    console.log('Here init article model ' + this.url);
    this.on("change:gallery", function( model ){
      console.log('change');
      if( model.get( 'gallery' ) == null ){
        model.set({ 'gallery': [] });
      }
    });

    /*if( this.tags == null ){
      this.set( 'tags', [] );
    }*/
  },

});

var Articles = Backbone.Collection.extend({
  url: '/admin/articles',
  model: Article
});