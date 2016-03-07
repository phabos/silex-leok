$("nav .nav.navbar-nav .nav-item").click(function() {
  $("nav .nav.navbar-nav .nav-item").removeClass('active');
  jQuery(this).addClass('active');
});
