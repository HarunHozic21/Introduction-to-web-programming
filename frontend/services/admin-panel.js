$(document).ready(function () {
  const token = localStorage.getItem("user_token");
  const user = Utils.parseJwt(token)?.user;

  if (!user || user.role !== Constants.ADMIN_ROLE) {
    toastr.error("You are not authorized to view this page.");
    window.location.href = "index.html";
    return;
  }

  // Load products
  RestClient.get("products", function (data) {
    Utils.datatable("products-table", [
      { data: "product_id", title: "ID" },
      { data: "name", title: "Name" },
      { data: "brand", title: "Brand" },
      { data: "price", title: "Price" },
      { data: "stock", title: "Stock" }
    ], data);
  });

  // Load users
  RestClient.get("users", function (data) {
    Utils.datatable("users-table", [
      { data: "user_id", title: "ID" },
      { data: "first_name", title: "First Name" },
      { data: "last_name", title: "Last Name" },
      { data: "email", title: "Email" },
      { data: "role", title: "Role" }
    ], data);
  });

  // Load orders
  RestClient.get("orders", function (data) {
    Utils.datatable("orders-table", [
      { data: "order_id", title: "Order ID" },
      { data: "user_id", title: "User ID" },
      { data: "order_status", title: "Status" },
      { data: "total_price", title: "Total Price" },
      { data: "created_at", title: "Date" }
    ], data);
  });
});
