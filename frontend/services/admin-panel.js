const AdminPanel = (function() {

    // Centralized function to handle success and error callbacks for API requests
    function handleResponse(type, action) {
        return {
            success: function(response) {
                toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} ${action} successfully!`);
                // Use a short timeout to allow the user to see the message before reload
                setTimeout(() => location.reload(), 1000); 
            },
            error: function(xhr) {
                // Try to get a meaningful error message from the server's JSON response, otherwise show a generic message
                const message = xhr.responseJSON ? xhr.responseJSON.message : `Failed to ${action} ${type}.`;
                toastr.error(message);
            }
        };
    }

    // This function runs when the admin panel view is loaded. It initializes all tables.
    function initTables() {
        // Products Table
        RestClient.get("products", function(data) {
            Utils.datatable("products-table", [
                { data: "product_id", title: "ID" }, 
                { data: "name", title: "Name" }, 
                { data: "brand", title: "Brand" }, 
                { data: "price", title: "Price" }, 
                { data: "stock", title: "Stock" },
                { 
                    title: "Actions", 
                    data: "product_id", 
                    render: function(data) { 
                        return `<button class="btn btn-primary btn-sm" onclick="AdminPanel.openEditModal('product', ${data})">Edit</button> <button class="btn btn-danger btn-sm" onclick="AdminPanel.deleteItem('product', ${data})">Delete</button>`; 
                    } 
                }
            ], data);
        }, function(xhr) {
            console.error("Failed to load products:", xhr);
            toastr.error("Failed to load products");
        });

        // Users Table
        RestClient.get("users", function(data) {
            Utils.datatable("users-table", [
                { data: "user_id", title: "ID" }, 
                { data: "first_name", title: "First Name" }, 
                { data: "last_name", title: "Last Name" }, 
                { data: "email", title: "Email" }, 
                { data: "role", title: "Role" },
                { 
                    title: "Actions", 
                    data: "user_id", 
                    render: function(data) { 
                        return `<button class="btn btn-primary btn-sm" onclick="AdminPanel.openEditModal('user', ${data})">Edit</button> <button class="btn btn-danger btn-sm" onclick="AdminPanel.deleteItem('user', ${data})">Delete</button>`; 
                    } 
                }
            ], data);
        }, function(xhr) {
            console.error("Failed to load users:", xhr);
            toastr.error("Failed to load users");
        });

        // Orders Table
        RestClient.get("orders", function(data) {
            Utils.datatable("orders-table", [
                { data: "order_id", title: "Order ID" }, 
                { data: "user_id", title: "User ID" }, 
                { data: "order_status", title: "Status" }, 
                { data: "total_price", title: "Total" }, 
                { data: "created_at", title: "Date" },
                { 
                    title: "Actions", 
                    data: "order_id", 
                    render: function(data) { 
                        return `<button class="btn btn-primary btn-sm" onclick="AdminPanel.openEditModal('order', ${data})">Edit</button> <button class="btn btn-danger btn-sm" onclick="AdminPanel.deleteItem('order', ${data})">Delete</button>`; 
                    } 
                }
            ], data);
        }, function(xhr) {
            console.error("Failed to load orders:", xhr);
            toastr.error("Failed to load orders");
        });
    }

    // Generic function to clear a form and open the modal for ADDING a new item
    function openAddModal(type) {
        $(`#${type}Form`)[0].reset();
        $(`#${type}_id`).val(''); // Clear the hidden ID field
        $(`#${type}ModalLabel`).text(`Add New ${type.charAt(0).toUpperCase() + type.slice(1)}`);
        $(`#${type}Modal`).modal('show'); // Add this line to actually show the modal
    }

    // Generic function to fetch item data and open the modal for EDITING
    function openEditModal(type, id) {
        console.log(`Opening edit modal for ${type} with ID: ${id}`);
        RestClient.get(`${type}s/${id}`, function(item) {
            console.log(`Received ${type} data:`, item);
            $(`#${type}ModalLabel`).text(`Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`);
            
            // Handle different response structures
            const itemData = item.data || item; // Some APIs wrap data in a 'data' property
            
            // Loop through the received item data and populate the form fields
            for (const key in itemData) {
                const field = $(`#${type}_${key}`);
                if (field.length) {
                    console.log(`Setting field ${type}_${key} to:`, itemData[key]);
                    field.val(itemData[key]);
                }
            }
            $(`#${type}Modal`).modal('show');
        }, function(xhr) {
            console.error(`Failed to load ${type}:`, xhr);
            const errorMsg = xhr.responseJSON?.message || xhr.responseText || `Failed to load ${type} details`;
            toastr.error(errorMsg);
        });
    }

    // Individual "save" functions that call the generic saveData function
    function saveProduct() { saveData('product'); }
    function saveUser() { saveData('user'); }
    function saveOrder() { saveData('order'); }

    // A generic function to handle both ADD and UPDATE logic
    function saveData(type) {
        const data = {};
        // Serialize form data into a simple object
        $(`#${type}Form`).serializeArray().forEach(field => data[field.name] = field.value);
        
        console.log(`Saving ${type} data:`, data);
        
        // For users, if the password field is empty, don't include it in the update
        if (type === 'user' && !data.password) {
            delete data.password;
        }

        const id = data[`${type}_id`];
        delete data[`${type}_id`]; // Remove the ID from the data payload

        const updateCallbacks = {
            success: function(response) {
                console.log(`${type} updated successfully:`, response);
                toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} updated successfully!`);
                $(`#${type}Modal`).modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                console.error(`Failed to update ${type}:`, xhr);
                const message = xhr.responseJSON?.message || xhr.responseText || `Failed to update ${type}.`;
                toastr.error(message);
            }
        };
        
        const addCallbacks = {
            success: function(response) {
                console.log(`${type} added successfully:`, response);
                toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} added successfully!`);
                $(`#${type}Modal`).modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                console.error(`Failed to add ${type}:`, xhr);
                const message = xhr.responseJSON?.message || xhr.responseText || `Failed to add ${type}.`;
                toastr.error(message);
            }
        };

        if (id && id !== '') { // If an ID exists, it's an UPDATE (PUT)
            console.log(`Updating ${type} with ID ${id}`);
            RestClient.put(`${type}s/${id}`, data, updateCallbacks.success, updateCallbacks.error);
        } else { // Otherwise, it's a new item (POST)
            console.log(`Creating new ${type}`);
            RestClient.post(`${type}s`, data, addCallbacks.success, addCallbacks.error);
        }
    }

    // A generic function for DELETING an item
    function deleteItem(type, id) {
        console.log(`Attempting to delete ${type} with ID: ${id}`);
        if (confirm(`Are you sure you want to delete this ${type}? This action cannot be undone.`)) {
            const callbacks = {
                success: function(response) {
                    console.log(`${type} deleted successfully:`, response);
                    toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`);
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    console.error(`Failed to delete ${type}:`, xhr);
                    const message = xhr.responseJSON?.message || xhr.responseText || `Failed to delete ${type}.`;
                    toastr.error(message);
                }
            };
            RestClient.delete(`${type}s/${id}`, {}, callbacks.success, callbacks.error);
        }
    }

    // Make the necessary functions public so they can be called from the HTML (e.g., onclick)
    return {
        init: initTables,
        openAddModal: openAddModal,
        openEditModal: openEditModal,
        saveProduct: saveProduct,
        saveUser: saveUser,
        saveOrder: saveOrder,
        deleteItem: deleteItem
    };
})();

// Initialize the admin panel when the page loads
$(document).ready(function() {
    // Check if we're on the admin panel page
    if (window.location.hash === '#admin_panel' || $('#products-table').length > 0) {
        AdminPanel.init();
    }
});

// Also initialize when navigating to admin panel via SPA routing
$(document).on('spapp.page.loaded', function(event, data) {
    if (data.route === 'admin_panel') {
        AdminPanel.init();
    }
});