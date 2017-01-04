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
  app.baseUrl = '/';
  app.IHM = 'PRO';
  if (document.location.href.match('/pro/app/patient/#!'))
    app.IHM = 'PATIENT';

  if (window.location.port === '') {  // if production
    // Uncomment app.baseURL below and
    // set app.baseURL to '/your-pathname/' if running from folder in production
    // app.baseUrl = '/polymer-starter-kit/';
  }

  //update the correct content when a tab is click
  app.updateContent= function(event) {
    app.update(event.currentTarget.getAttribute("data-route"));
  };

  // will call the method refresh of the selected "content element".
  app.update = function(routeToMatch) {
    var elements = this.$.contentPages.children;
    for (var i=0; i< elements.length; i++) {
      var el = elements[i];
      //refresh if selected icon
      if (el.getAttribute("data-route") == routeToMatch)
        el.children[0].refresh();
    }
  };

  app.updateCurrentRoute = function() {
    console.log("updateCurrentRoute");
    var elements = this.$.contentPages.children;
    for (var i=0; i< elements.length; i++) {
      var el = elements[i];
      //refresh if selected icon
      if (el.getAttribute("data-route") == app.route)
        el.children[0].refresh();
    }
  };

  app.refreshPatientList = function() {
    this.$.patientsListService.refresh();
  };

  app.activateSpinner = function() {
    this.$.spinnerBox.removeAttribute("hidden");
    this.$.spinnerBox.children[0].active = true;
  };

  app.desactivateSpinner = function() {
    this.$.spinnerBox.setAttribute("hidden", true);
    this.$.spinnerBox.children[0].active = false;
  };

  app.displayInstalledToast = function() {
    // Check to make sure caching is actually enabled—it won't be in the dev environment.
    if (!Polymer.dom(document).querySelector('platinum-sw-cache').disabled) {
      Polymer.dom(document).querySelector('#caching-complete').show();
    }
  };

  //toggle form for create patient
  app.toggleForm= function() {
    this.$.patientForm.toggle();
  };

  //responsive function to adjust tab width in the menu-toolbar in function of client browser width
  app.setTabSize = function() {
      //browser window width
      var browserWidth = window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth;

      //set bottom margin for logout button
      if (browserWidth < 1024)
        document.getElementById("headerPanel").children[0].style.bottom = "56px";
      else
        document.getElementById("headerPanel").children[0].style.bottom = "64px";

      if (browserWidth > 340) {
        var ratio = 0.90;
        if (browserWidth > 700 && browserWidth < 850)
          ratio = 1.2;
        else if (browserWidth > 600 && browserWidth < 701)
          ratio = 1.5;
        else if (browserWidth < 601)
          ratio = 0.85;

         //menu tab responsive set
        var listChoices = document.getElementsByTagName("paper-tab");
        // ratio calculate the widh appropriate for a tab of the menu in function of the parent element (paper-tabs) size
        var choiceWidth = document.getElementsByTagName("paper-tabs")[0].offsetWidth / listChoices.length;
//        if (!(browserWidth < 601 && listChoices.length == 5))
        if (document.getElementsByTagName("paper-tabs")[0].offsetWidth > 600 || (browserWidth > 380 && browserWidth < 601))
         for (var i=0; i<listChoices.length; i++)
            listChoices[i].style.width = (ratio * choiceWidth) + "px";

        //force click on another tab and then actual route (fixed drawing of the yellow tabBarSelector)
        var actual = app.route;
        if (actual == "infos") {
          var tab = (app.IHM == "PATIENT") ? "resume" : "home";
          app.clickOnTab(tab, 150);
        }
        else
          app.clickOnTab("infos", 150);
        app.clickOnTab(actual, 200);
      }
  };

  app.clickOnTab= function(tabName, time) {
    setTimeout(function() {
        app.route = tabName;
    }, time);
  };

  app.mediaSizeChanged= function(event) {
    //Ugly but fix double call at the loading;
    if (app.sizeChangedCalled) {
      setTimeout(function() {
        //this == event (caused by .bind(event))
        // if true query matches => media width < 601px
        if (this.detail.value)
          app.$.headerPanel.$.mainContainer.style.top = "56px";
        // else media width > 600 px
        else
          app.$.headerPanel.$.mainContainer.style.top = "64px";

        setTimeout(app.setTabSize, 100);

      }.bind(event), 250);
    }
    app.sizeChangedCalled = true;
  };

  //Handle on-value-changed in the patient search bar
  //Hide patient in the list which not match with the pattern enter in the search bar
  app.searchChanged= function() {
    var list = this.$.listPatients.children[0].children;
    var patt = new RegExp("^" + this.search + ".*")
    for (var i=0; i<list.length; i++) {
      var itemlist = list[i];
      if (itemlist.tagName === "A") {
        if (this.search === "")
          itemlist.removeAttribute("hidden");
        else if (this.patients[i].firstName.toLowerCase().match(patt) || this.patients[i].lastName.toLowerCase().match(patt) || this.patients[i].fullName.toLowerCase().match(patt))
          itemlist.removeAttribute("hidden");
        else
          itemlist.setAttribute("hidden", true);
      }
    }
  };

  app.writeError = function(text) {
    app.$.toast.text = text;
    app.$.toast.style.backgroundColor = "red";
    app.$.toast.show();
  }

  app.writeToast = function(text) {
    app.$.toast.text = text;
    app.$.toast.style.backgroundColor = "#454646";
    app.$.toast.show();
  }

  // See https://github.com/Polymer/polymer/issues/1381
  window.addEventListener('WebComponentsReady', function() {
    // imports are loaded and elements have been registered
    // will launch update function in order to call refresh on all element and get all the data needed by each element
    app.$.paperDrawerPanel.responsiveWidth = "800px";
    app.$.menuToolbar.hideScrollButtons = true;
    app.refreshPatientList();
  });

})(document);
