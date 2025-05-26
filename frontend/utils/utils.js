const Utils = {
    datatable: function (table_id, columns, data, pageLength = 15) {
        if (!table_id || !columns || !data) {
            console.error('Invalid parameters for datatable initialization');
            return;
        }

        const tableSelector = `#${table_id}`;
        if ($.fn.dataTable.isDataTable(tableSelector)) {
            $(tableSelector).DataTable().destroy();
        }

        $(tableSelector).DataTable({
            data: data,
            columns: columns,
            pageLength: pageLength,
            lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });
    },

    parseJwt: function(token) {
        if (!token) return null;
        
        try {
            const tokenParts = token.split('.');
            if (tokenParts.length !== 3) throw new Error('Invalid token format');
            
            const payload = atob(tokenParts[1]);
            return JSON.parse(payload);
        } catch (e) {
            console.error("Error parsing JWT token:", e);
            return null;
        }
    },

    formatDate: function(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },

    formatPrice: function(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },

    validateEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    showError: function(message) {
        toastr.error(message);
    },

    showSuccess: function(message) {
        toastr.success(message);
    }
};
