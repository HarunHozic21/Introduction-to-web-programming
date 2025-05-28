const Router = {
    routes: {
        '/': 'tpl/view_1.html',
        '/login': 'tpl/login.html',
        '/registration': 'tpl/registration.html',
        '/cart': 'tpl/cart.html',
        '/product': 'tpl/product.html'
    },

    init: function() {
        window.addEventListener('hashchange', () => this.loadContent());
        this.loadContent();
    },

    loadContent: function() {
        const path = window.location.hash.slice(1) || '/';
        const templatePath = this.routes[path] || this.routes['/'];
        
        fetch(templatePath)
            .then(response => response.text())
            .then(html => {
                document.getElementById('app-content').innerHTML = html;
                UserService.generateMenuItems();
                // Initialize any page-specific scripts
                if (typeof window.pageInit === 'function') {
                    window.pageInit();
                }
            })
            .catch(error => {
                console.error('Error loading template:', error);
                Utils.showError('Failed to load page content');
            });
    },

    navigate: function(path) {
        window.location.hash = path;
    }
};
