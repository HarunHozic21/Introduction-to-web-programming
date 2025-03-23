var app = $.spapp({
  defaultView  : "#view_1",
  templateDir  : "./tpl/",
  pageNotFound : "error_404"
});

app.route({
  view : "view_1",
  load : "view_1.html",
  onCreate: function() {  },
  onReady: function() {  }
});

app.route({
  view : "about",
  load : "about.html",
  onCreate: function() {  },
  onReady: function() {  }
});

app.route({
  view : "registration",
  load : "registration.html",
  onCreate: function() {  },
  onReady: function() {  }
});

app.route({
  view : "login",
  load : "login.html",
  onCreate: function() {  },
  onReady: function() {  }
});

app.route({
  view : "cart",
  load : "cart.html",
  onCreate: function() {  },
  onReady: function() {  }
});

app.run();