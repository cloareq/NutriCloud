/*
Copyright (c) 2015 The Polymer Project Authors. All rights reserved.
This code may only be used under the BSD style license found at http://polymer.github.io/LICENSE.txt
The complete set of authors may be found at http://polymer.github.io/AUTHORS.txt
The complete set of contributors may be found at http://polymer.github.io/CONTRIBUTORS.txt
Code distributed by Google as part of the polymer project is also
subject to an additional IP rights grant found at http://polymer.github.io/PATENTS.txt
*/

(function(document) {
  'use strict';

  // Grab a reference to our auto-binding template
  // and give it some initial binding values
  // Learn more about auto-binding templates at http://goo.gl/Dx1u2g
  var app = document.querySelector('#app');

  // Sets app default base URL
  app.baseUrl = '/patient/app/#!/';
  if (window.location.port === '') { // if production
    // Uncomment app.baseURL below and
    // set app.baseURL to '/your-pathname/' if running from folder in production
    // app.baseUrl = '/polymer-starter-kit/';
  }

  app.activateSpinner = function() {
    this.$.spinnerBox.removeAttribute("hidden");
    this.$.spinnerBox.children[0].active = true;
  };

  app.desactivateSpinner = function() {
    this.$.spinnerBox.setAttribute("hidden", true);
    this.$.spinnerBox.children[0].active = false;
  };

  app.disconnected = function() {
    this.activateSpinner();
    document.location.href = "/connection";
  };

  //update the correct content when a tab is click
  app.updateContent = function(event) {
    app.update(event.currentTarget.getAttribute("data-route"));
  };

  // will call the method refresh of the selected "content element".
  app.update = function(routeToMatch) {
    var elements = this.$.contentPages.children;
    for (var i = 0; i < elements.length; i++) {
      var el = elements[i];
      //refresh if selected icon
      if (el.getAttribute("data-route") == routeToMatch)
        el.children[0].refresh();
    }
  };

  app.displayInstalledToast = function() {
    // Check to make sure caching is actually enabled—it won't be in the dev environment.
    if (!Polymer.dom(document).querySelector('platinum-sw-cache').disabled) {
      Polymer.dom(document).querySelector('#caching-complete').show();
    }
  };

  app.setTabSize = function() {
    var list = document.querySelectorAll('paper-tab');
    var browserWidth = window.innerWidth ||
      document.documentElement.clientWidth ||
      document.body.clientWidth;

    var tabsize = 120;
    if (browserWidth > 700)
      tabsize = browserWidth / 6.2; //divide by 6 tab + 0.2 for margin
    for (var i = 0; i < list.length; i++)
      list[i].style.width = tabsize + "px";
  };

  // Listen for template bound event to know when bindings
  // have resolved and content has been stamped to the page
  app.addEventListener('dom-change', function() {
    //console.log('Our app is ready to rock!');
  });

  // See https://github.com/Polymer/polymer/issues/1381
  window.addEventListener('WebComponentsReady', function() {
    // imports are loaded and elements have been registered
    app.$.idService.getUserId();
    //app.init();
  });

  app.ajaxIdGet = function(event) {
    if (app.userId == undefined)
      app.userId = Number(event.detail.response);
    app.init();
  };

  app.init = function() {
    app.$.menuToolbar.hideScrollButtons = true;
    app.setTabSize();
    app.update(app.route);
  };

})(document);
