// Setup AJAX defaults
$.ajaxSetup({
    beforeSend: function (xhr) {
        const token = localStorage.getItem("user_token");
        if (token) {
            xhr.setRequestHeader("Authorization", "Bearer " + token);
        }
    },
});

// Check if RestClient already exists to prevent redeclaration
if (typeof window.RestClient === 'undefined') {
    window.RestClient = {
        request: function (url, method, data, callback, error_callback) {
            $.ajax({
                url: window.Constants.PROJECT_BASE_URL + url,
                type: method,
                contentType: "application/json",
                data: JSON.stringify(data),
            })
            .done(function (response) {
                if (callback) callback(response);
            })
            .fail(function (jqXHR) {
                if (error_callback) {
                    error_callback(jqXHR);
                } else {
                    toastr.error(jqXHR.responseJSON?.message || "An unexpected error occurred.");
                }
            });
        },

        get: function (url, callback, error_callback) {
            $.ajax({
                url: window.Constants.PROJECT_BASE_URL + url,
                type: "GET",
            })
            .done(function (response) {
                if (callback) callback(response);
            })
            .fail(function (jqXHR) {
                if (error_callback) {
                    error_callback(jqXHR);
                } else {
                    toastr.error(jqXHR.responseJSON?.message || "An unexpected error occurred.");
                }
            });
        },

        post: function (url, data, callback, error_callback) {
            this.request(url, "POST", data, callback, error_callback);
        },

        put: function (url, data, callback, error_callback) {
            this.request(url, "PUT", data, callback, error_callback);
        },

        delete: function (url, data, callback, error_callback) {
            this.request(url, "DELETE", data, callback, error_callback);
        },

        patch: function (url, data, callback, error_callback) {
            this.request(url, "PATCH", data, callback, error_callback);
        },
    };
}