/*$("nav .nav.navbar-nav .nav-item").click(function() {
  $("nav .nav.navbar-nav .nav-item").removeClass('active');
  jQuery(this).addClass('active');
});*/

/*******************************/
/*******    App Logic    *******/
/*******************************/
var App = {
  anim: 'false',
  help: function() {
    console.log("help");
  },
  animateResponse: function(response) {
    if(App.anim == 'true'){
      jQuery('#message').html(response);
      if(jQuery('.btn-primary i'))
        jQuery('.btn-primary i').removeClass('fa-spin');
      return;
    }
    App.anim = 'true';
    jQuery('#message').html(response);
    $('#message').css({top: 80});
    setTimeout(function(){ $('#message').css({top: -200}); App.anim = 'false'; }, 3000);
    if(jQuery('.btn-primary i'))
      jQuery('.btn-primary i').removeClass('fa-spin');
  },
  animateBtn: function() {
    jQuery('.btn-primary i').addClass('fa-spin');
  }
};