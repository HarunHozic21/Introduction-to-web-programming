let ProductService = {
  init: function () {
    ProductService.loadAllProducts();
  },

  loadAllProducts: function () {
    RestClient.get("products/available", function (products) {
      let html = "";
      products.forEach(product => {
        html += `
          <div class="col mb-5">
            <div class="card h-100">
              ${product.stock < 1 ? '<div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sold Out</div>' : ""}
              <img class="card-img-top" src="${product.image_url || 'https://via.placeholder.com/450x300'}" alt="${product.name}" />
              <div class="card-body p-4 text-center">
                <h5 class="fw-bolder">${product.name}</h5>
                $${parseFloat(product.price).toFixed(2)}
              </div>
              <div class="card-footer p-4 pt-0 border-top-0 bg-transparent text-center">
                <button class="btn btn-outline-dark mt-auto" onclick="ProductService.viewProduct(${product.product_id})">View Product</button>
              </div>
            </div>
          </div>
        `;
      });

      $("#products-container").html(html);
    }, function (xhr) {
      console.error("Error loading products", xhr);
      $("#products-container").html(`<div class="col-12 text-center text-danger">Failed to load products.</div>`);
    });
  },

  viewProduct: function (id) {
    localStorage.setItem("selected_product_id", id);
    window.location.href = "product-detail.html";
  }
};
