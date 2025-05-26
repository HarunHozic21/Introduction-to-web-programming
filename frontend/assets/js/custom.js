var app = $.spapp({
  defaultView: "view_1",
  templateDir: "./tpl/",
  pageNotFound: "error_404"
});

// Initialize navigation menu
UserService.generateMenuItems();

app.route({
  view: "view_1",
  load: "view_1.html",
  onCreate: function() {
    UserService.generateMenuItems();
  },
  onReady: function() {
    UserService.generateMenuItems();
  }
});

app.route({
  view: "about",
  load: "about.html",
  onCreate: function() {
    UserService.generateMenuItems();
  },
  onReady: function() {
    UserService.generateMenuItems();
  }
});

app.route({
  view: "cart",
  load: "cart.html",
  onCreate: function() {
    UserService.generateMenuItems();
  },
  onReady: function() {
    UserService.generateMenuItems();
  }
});

app.run();