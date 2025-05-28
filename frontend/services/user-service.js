var UserService = {
  init: function () {
    const token = localStorage.getItem("user_token");
    if (token && token !== undefined) {
      const user = Utils.parseJwt(token).user;
      if (user.role === Constants.ADMIN_ROLE) {
        window.location.replace("admin.html");
      } else {
        window.location.replace("index.html");
      }
    }

    $("#login-form").validate({
      submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        UserService.login(entity);
      },
    });
  },

  login: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        localStorage.setItem("user_token", result.data.token);
        const user = Utils.parseJwt(result.data.token).user;

        if (user.role === Constants.ADMIN_ROLE) {
          window.location.replace("admin.html");
        } else {
          window.location.replace("index.html");
        }
      },
      error: function (xhr) {
        toastr.error(xhr?.responseText ? xhr.responseText : "Login failed");
      },
    });
  },

  logout: function () {
    localStorage.clear();
    window.location.replace("login.html");
  },

  generateMenuItems: function () {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (user && user.role) {
      let nav = "";

      if (user.role === Constants.USER_ROLE) {
        nav += `
          <li class="nav-item"><a class="nav-link" href="#view_1">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#product">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="#cart">Cart</a></li>
          <li class="nav-item"><button class="btn btn-danger" onclick="UserService.logout()">Logout</button></li>
        `;
      } else if (user.role === Constants.ADMIN_ROLE) {
        nav += `
          <li class="nav-item"><a class="nav-link" href="admin.html">Admin Panel</a></li>
          <li class="nav-item"><button class="btn btn-danger" onclick="UserService.logout()">Logout</button></li>
        `;
      }

      $(".navbar-nav").html(nav);
    } else {
      window.location.replace("login.html");
    }
  }
};
