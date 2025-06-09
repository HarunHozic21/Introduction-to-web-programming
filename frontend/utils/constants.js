// Check if Constants already exists to prevent redeclaration
if (typeof window.Constants === 'undefined') {
    window.Constants = {
        PROJECT_BASE_URL: "https://localhost/project/Introduction-to-web-programming/backend/",
        USER_ROLE: "customer",
        ADMIN_ROLE: "admin",
    };
}