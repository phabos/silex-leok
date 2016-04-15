/*******************************/
/*******    App Logic    *******/
/*******************************/
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

/*******************************/
/*******  Settings View  *******/
/*******************************/
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
    "click .settingsedit button.btn-primary": "saveSettings"
  },

  saveSettings: function( event ){
    var obj = this;
    App.animateBtn();
    if( this.xhr == true )
      return;

    this.xhr = true;

    this.$el.find(".settingsedit input[data-field]").each(function(){
      obj.settings.set(this.getAttribute("data-field"), this.value);
    });

    this.settings.save({},{
      success:function(model, response){
        App.animateResponse( 'Settings mis à jour' );
        obj.xhr = false;
      },
      error:function(err){
        alert( err );
      }
    });

  }

});

/*******************************/
/*******  Articles List  ********/
/*******************************/
var ArtilcesList = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(){
    this.articles = new Articles;
    this.render();
  },

  render: function(){
    var template = _.template( $("#articles_list_template").html() );
    var obj = this;

    this.articles.fetch({
      success: function (articles) {
        obj.$el.html( template( { articles: obj.articles.toJSON() } ) );
      }
    });
  },

});

/*******************************/
/*******  Article Edit  ********/
/*******************************/
var ArtilcesEdit = Backbone.View.extend({
  el: $("#main_content"),

  initialize: function(options){
    console.log('article edit view init ' + options.id);
    this.article = new Article({ id: options.id });
    this.render();
  },

  events: {
    "click .articleedit button.btn-primary": "saveArticle",
    "click .img-delete": "deleteImg"
  },

  render: function(){
    var template = _.template( $("#articles_edit_template").html() );
    var obj = this;

    this.article.fetch({
      success: function (article) {
        obj.$el.html( template( { article: obj.article.toJSON() } ) );
        var editor = new MediumEditor('.editable');
        obj.observeFileupload();
      }
    });

  },

  observeFileupload: function() {
    var obj = this;
    var dropzone = jQuery(".dropzone");

    dropzone.on('dragenter', function(e){
      e.preventDefault();
    });

    dropzone.bind('dragover', function(e){
      e.preventDefault();
      $(this).addClass('drag-over');
    });

    dropzone.bind('dragleave', function(e){
      e.preventDefault();
      $(this).removeClass('drag-over');
    });

    dropzone.on('drop', function(e){
      var target = jQuery(e.target).parents( '.dropzone' ).attr('id');
      e.stopPropagation();
      e.preventDefault();
      App.animateResponse('Envoi en cours...');
      var filesArray = e.originalEvent.dataTransfer.files;
      for (var i=0; i<filesArray.length; i++) {
          obj.sendFile(filesArray[i], target);
      }
    });
  },

  sendFile: function (file, target) {
    var obj = this;
    var uri = "/admin/send-images";
    var xhr = new XMLHttpRequest();
    var fd = new FormData();

    xhr.open("POST", uri, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Handle response.
        result = JSON.parse( xhr.responseText );
        App.animateResponse( result.msg );
        if( result.imageUrl != '' ) {
          if( target == 'dropzone' ) {
            jQuery('#main-article-img').attr({ src: result.imageUrl });
            // update article image model
            obj.article.set({ image: result.imageUrl });
          }
          if( target == 'gal-dropzone' ) {
            var gallery = obj.article.get('gallery');
            jQuery('#main-article-gal-container').append( '<div class="img-box" data-count="' + gallery.length + '"><img src="' + result.imageUrl + '" /><i class="fa fa-trash-o img-delete" aria-hidden="true"></i></div>' );
            gallery.push( result.imageUrl );
            obj.article.set('gallery', gallery);
          }
        }
      }
    };
    fd.append('myFile', file);
    // Initiate a multipart/form-data upload
    xhr.send(fd);
  },

  deleteImg: function(e) {
    var elt = jQuery(e.target).parents( '.img-box' );
    var gallery = this.article.get('gallery');
    var index = gallery.indexOf( elt.find( 'img' ).attr( 'src' ) );
    if( index > -1 ) {
      gallery.splice( index, 1 );
      this.article.set('gallery', gallery);
      elt.remove();
    }else{
      console.log( 'l image n existe pas !' );
    }
  },

  saveArticle: function() {
    var obj = this;
    App.animateBtn();

    this.$el.find(".articleedit input[data-field]").each(function(){
      obj.article.set(this.getAttribute("data-field"), this.value);
    });

    this.article.set({ content: this.$el.find("div#postContent").html() });

    this.article.save({},{
      success:function(model, response){
        App.animateResponse( 'Article mis à jour' );
      },
      error:function(err){
        alert( err );
      }
    });
  }

});

/*******************************/
/*******    Home View    *******/
/*******************************/
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
