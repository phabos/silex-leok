/******** INIT APP / SET DEPENDENCIES ********/
var app = angular.module('leoKnitApp', ['ngRoute', 'angular-loading-bar', 'ngAnimate', 'ngSanitize', 'angular-carousel']);
/******** CONFIG ********/
// Symbols
app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{*');
    $interpolateProvider.endSymbol('*}');
});
// Loader
app.config(function(cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = true;
});
// Router
app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/', {
            templateUrl: '/webroot/partials/article-list.html',
            controller: 'ArticleCtrl'
        }).when('/contact', {
            templateUrl: '/webroot/partials/contact-form.html',
            controller: 'ContactCtrl'
        });
        /*.
              otherwise({
                redirectTo: '/phones'
              });*/
    }
]);
/******** ARTICLE CONTROLLER ********/
app.controller('ArticleCtrl', function($scope, $http, $routeParams, getHttp, mainDomain, animateArticle) {
    $scope.pageClass = 'page-home';
    $scope.offset = 0;
    $scope.showMore = 0;
    $scope.showNoMore = 0;
    // More article button
    $scope.loadMoreArticles = function() {
            grabMoreArticle();
        }
        // Global functio to get the article
    var grabMoreArticle = function() {
            $scope.showMore = 0;
            getHttp.httpRequest(mainDomain.name + '/api/article.json?offset=' + $scope.offset).success(function(data, status, headers, config) {
                if (data.length > 0) {
                    $scope.showMore = 1;
                } else {
                    $scope.showMore = 0;
                }
                if (data.length == 0) {
                    $scope.showNoMore = 1;
                }
                if (angular.isUndefined($scope.articles)) $scope.articles = [];
                $scope.articles = $scope.articles.concat(data);
            });
            $scope.offset++;
        }
        // Fire when controller is called
    grabMoreArticle();
    $scope.loadDetailArticle = function(elt, pos) {
        animateArticle.beforeLoadContent(elt, pos);
    }
    $scope.hideContent = function() {
        animateArticle.hideContent();
    }
    /*$scope.$on("$locationChangeStart", function(event) {
        var elt = jQuery('#grid_item_' + event.targetScope.$id);
        animateArticle.beforeShowDetail(elt);
    });*/
});
/******** ARTICLE DETAIL CONTROLLER ********/
/*app.controller('ArticleDetailCtrl', function($scope, $http, $routeParams, $location, getHttp, mainDomain, animateArticle) {
    $scope.pageClass = 'page-detail';
    getHttp.httpRequest(mainDomain.name + '/api/article.json?offset=' + $scope.offset).success(function(data, status, headers, config) {
        articleId = $routeParams.articleId;
        console.log(data[articleId].gallery);
        if (angular.isUndefined(data[articleId])) $location = '/';
        $scope.article = data[articleId];
        animateArticle.showDetail();
    });
    $scope.$on("$locationChangeStart", function(event) {
        animateArticle.hideDetail();
    });
});*/
app.controller('ContactCtrl', function($scope, $http, $routeParams, $location, getHttp, mainDomain, animateArticle) {
    /*animateArticle.loadContent();
    $scope.$on("$locationChangeStart", function(event) {
        animateArticle.hideDetail();
    });*/
});
/******** Factory ********/
app.value('current', -1);
app.value('lockScroll', false);
app.value('xscroll', '');
app.value('yscroll', '');
app.value('isAnimating', false);
app.value('item', '');
app.value('article', '');
app.factory('animateArticle', function(current, lockScroll, xscroll, yscroll, isAnimating) {
    this.beforeLoadContent = function(elt, pos) {
        console.log(elt + ' ' + pos);
        var obj = this;
        item = document.getElementById('grid_item_' + elt);
        article = document.getElementById('grid_article_' + elt);

        if(isAnimating || current === pos) {
            return false;
        }
        isAnimating = true;
        // index of current item
        current = pos;
        // simulate loading time..
        item.classList.add('grid__item--loading');

        setTimeout(function() {
            item.classList.add('grid__item--animate');
            // reveal/load content after the last element animates out (todo: wait for the last transition to finish)
            setTimeout(function() { obj.loadContent(item); }, 500);
        }, 1000);
    };
    this.loadContent = function() {
        console.log('loading content');
        var gridEl = document.getElementById('theGrid'),
            gridItemsContainer = gridEl.querySelector('section.grid'),
            contentItemsContainer = gridEl.querySelector('section.content'),
            contentItems = contentItemsContainer.querySelectorAll('.content__item'),
            closeCtrl = contentItemsContainer.querySelector('.close-button'),
            bodyEl = document.body,
            obj= this;
        // add expanding element/placeholder
        var dummy = document.createElement('div');
        dummy.className = 'placeholder';

        // set the width/heigth and position
        dummy.style.WebkitTransform = 'translate3d(' + (item.offsetLeft - 5) + 'px, ' + (item.offsetTop - 5) + 'px, 0px) scale3d(' + item.offsetWidth/gridItemsContainer.offsetWidth + ',' + item.offsetHeight/this.getViewport('y') + ',1)';
        dummy.style.transform = 'translate3d(' + (item.offsetLeft - 5) + 'px, ' + (item.offsetTop - 5) + 'px, 0px) scale3d(' + item.offsetWidth/gridItemsContainer.offsetWidth + ',' + item.offsetHeight/this.getViewport('y') + ',1)';

        // add transition class
        dummy.classList.add('placeholder--trans-in');

        // insert it after all the grid items
        gridItemsContainer.appendChild(dummy);

        // body overlay
        bodyEl.classList.add('view-single');

        setTimeout(function() {
            // expands the placeholder
            dummy.style.WebkitTransform = 'translate3d(-5px, ' + (obj.scrollY() - 5) + 'px, 0px)';
            dummy.style.transform = 'translate3d(-5px, ' + (obj.scrollY() - 5) + 'px, 0px)';
            // disallow scroll
            window.addEventListener('scroll', obj.noscroll(obj));
        }, 25);

        this.onEndTransition(dummy, function() {
            // add transition class
            dummy.classList.remove('placeholder--trans-in');
            dummy.classList.add('placeholder--trans-out');
            // position the content container
            contentItemsContainer.style.top = obj.scrollY() + 'px';
            // show the main content container
            contentItemsContainer.classList.add('content--show');
            // show content item:
            article.classList.add('content__item--show');
            // show close control
            closeCtrl.classList.add('close-button--show');
            // sets overflow hidden to the body and allows the switch to the content scroll
            bodyEl.classList.add('noscroll');

            isAnimating = false;
        });
    };
    this.hideContent = function() {
        console.log('hidding content');
        var gridItem = item,
            contentItem = article,
            gridEl = document.getElementById('theGrid'),
            contentItemsContainer = gridEl.querySelector('section.content'),
            closeCtrl = contentItemsContainer.querySelector('.close-button'),
            bodyEl = document.body,
            gridItemsContainer = gridEl.querySelector('section.grid'),
            obj = this;

        contentItem.classList.remove('content__item--show');
        contentItemsContainer.classList.remove('content--show');
        closeCtrl.classList.remove('close-button--show');
        bodyEl.classList.remove('view-single');

        setTimeout(function() {
            var dummy = gridItemsContainer.querySelector('.placeholder');

            bodyEl.classList.remove('noscroll');

            dummy.style.WebkitTransform = 'translate3d(' + gridItem.offsetLeft + 'px, ' + gridItem.offsetTop + 'px, 0px) scale3d(' + gridItem.offsetWidth/gridItemsContainer.offsetWidth + ',' + gridItem.offsetHeight/obj.getViewport('y') + ',1)';
            dummy.style.transform = 'translate3d(' + gridItem.offsetLeft + 'px, ' + gridItem.offsetTop + 'px, 0px) scale3d(' + gridItem.offsetWidth/gridItemsContainer.offsetWidth + ',' + gridItem.offsetHeight/obj.getViewport('y') + ',1)';

            obj.onEndTransition(dummy, function() {
                // reset content scroll..
                contentItem.parentNode.scrollTop = 0;
                gridItemsContainer.removeChild(dummy);
                gridItem.classList.remove('grid__item--loading');
                gridItem.classList.remove('grid__item--animate');
                lockScroll = false;
                window.removeEventListener( 'scroll', obj.noscroll );
            });

            // reset current
            current = -1;
        }, 25);
    };
    this.onEndTransition = function( el, callback ) {
        var support = { transitions: Modernizr.csstransitions },
            transEndEventNames = { 'WebkitTransition': 'webkitTransitionEnd', 'MozTransition': 'transitionend', 'OTransition': 'oTransitionEnd', 'msTransition': 'MSTransitionEnd', 'transition': 'transitionend' },
            transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ];
        var onEndCallbackFn = function( ev ) {
            if( support.transitions ) {
                if( ev.target != this ) return;
                this.removeEventListener( transEndEventName, onEndCallbackFn );
            }
            if( callback && typeof callback === 'function' ) { callback.call(this); }
        };
        if( support.transitions ) {
            el.addEventListener( transEndEventName, onEndCallbackFn );
        }
        else {
            onEndCallbackFn();
        }
    };
    this.getViewport = function( axis ) {
        var docElem = window.document.documentElement;
        var client, inner;
        if( axis === 'x' ) {
            client = docElem['clientWidth'];
            inner = window['innerWidth'];
        }
        else if( axis === 'y' ) {
            client = docElem['clientHeight'];
            inner = window['innerHeight'];
        }

        return client < inner ? inner : client;
    };
    this.scrollX = function () {
        var docElem = window.document.documentElement
        return window.pageXOffset || docElem.scrollLeft;
    };
    this.scrollY = function() {
        var docElem = window.document.documentElement
        return window.pageYOffset || docElem.scrollTop;
    };
    this.noscroll = function(obj) {
        if(!lockScroll) {
            lockScroll = true;
            xscroll = obj.scrollX();
            yscroll = obj.scrollY();
        }
        window.scrollTo(xscroll, yscroll);
    }
    return this;
});
app.factory('getHttp', function($http) {
    this.httpRequest = function(url) {
        return $http.get(url, {
            cache: true
        }).error(function(data, status, headers, config) {
            alert('Une erreur s\'est produite !');
        });
    };
    this.postHttpRequest = function(url, postData) {
        return $http.post(url, postData).error(function(data, status, headers, config) {
            alert('Une erreur s\'est produite !');
        });
    };
    return this;
});
app.factory('mainDomain', function($location) {
    return {
        name: $location.protocol() + "://" + $location.host()
    };
});
/* formulaire inscription newsletter */
/*tfjassApp.controller('contactFormController', function($scope, gethttp) {
    $scope.update = function(ctemail, ctmessage) {
        gethttp.postHttpRequest("{{ app.url_generator.generate('dataContact') }}", {
            email: ctemail,
            message: ctmessage
        }).success(function(data, status, headers, config) {
            alert(data.msg);
        });
        $scope.reset();
    };
    $scope.reset = function() {
        $scope.ctemail = '';
        $scope.ctmessage = '';
    };
    $scope.reset();
});*/
