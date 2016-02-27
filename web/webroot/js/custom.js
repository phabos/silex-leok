jQuery(document).ready(function($) {
    var sidebarEl = document.getElementById('theSidebar'),
    	menuCtrl = document.getElementById('menu-toggle'),
		menuCloseCtrl = sidebarEl.querySelector('.close-button');

    $('#header1').arctext({
        radius: 250
    });
    $('#header2').arctext({
        radius: 250,
        dir: -1
    });
    // hamburger menu button (mobile) and close cross
    menuCtrl.addEventListener('click', function() {
        if (!classie.has(sidebarEl, 'sidebar--open')) {
            classie.add(sidebarEl, 'sidebar--open');
        }
    });
    menuCloseCtrl.addEventListener('click', function() {
        if (classie.has(sidebarEl, 'sidebar--open')) {
            classie.remove(sidebarEl, 'sidebar--open');
        }
    });
});