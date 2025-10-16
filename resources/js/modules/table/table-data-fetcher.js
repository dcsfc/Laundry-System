/**
 * Table Data Fetcher - Handles API calls for data tables
 * Separated from main table logic for better organization
 */

/**
 * Base Data Fetcher Class
 */
class BaseTableDataFetcher {
    constructor(baseUrl = '/api') {
        this.baseUrl = baseUrl;
    }

    async makeRequest(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        console.log('Making request to:', url, 'with options:', options);
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        };

        const config = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }

    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        
        return this.makeRequest(url, {
            method: 'GET'
        });
    }

    async post(endpoint, data = {}) {
        return this.makeRequest(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async put(endpoint, data = {}) {
        return this.makeRequest(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async delete(endpoint) {
        return this.makeRequest(endpoint, {
            method: 'DELETE'
        });
    }
}

/**
 * Users Data Fetcher - Specific implementation for user management
 */
class UsersDataFetcher extends BaseTableDataFetcher {
    constructor() {
        super('/superadmin/users');
    }

    async getUsers(params = {}) {
        return this.get('', params);
    }

    async getUser(id) {
        return this.get(`/${id}`);
    }

    async createUser(userData) {
        return this.post('', userData);
    }

    async updateUser(id, userData) {
        return this.put(`/${id}`, userData);
    }

    async deleteUser(id) {
        console.log('deleteUser called with ID:', id);
        return this.post('/delete-ajax', { user_id: id });
    }

    async toggleUserStatus(id) {
        return this.post('/toggle-status', { user_id: id });
    }

    // Password reset functionality removed per client request

    async bulkDeleteUsers(ids) {
        return this.post('/bulk-delete', { ids });
    }
}

/**
 * Roles Data Fetcher - For role management
 */
class RolesDataFetcher extends BaseTableDataFetcher {
    constructor() {
        super('/api/roles');
    }

    async getRoles(params = {}) {
        return this.get('', params);
    }

    async getRole(id) {
        return this.get(`/${id}`);
    }

    async createRole(roleData) {
        return this.post('', roleData);
    }

    async updateRole(id, roleData) {
        return this.put(`/${id}`, roleData);
    }

    async deleteRole(id) {
        return this.delete(`/${id}`);
    }
}

/**
 * Permissions Data Fetcher - For permission management
 */
class PermissionsDataFetcher extends BaseTableDataFetcher {
    constructor() {
        super('/api/permissions');
    }

    async getPermissions(params = {}) {
        return this.get('', params);
    }

    async getPermission(id) {
        return this.get(`/${id}`);
    }

    async updatePermission(id, permissionData) {
        return this.put(`/${id}`, permissionData);
    }
}

/**
 * Orders Data Fetcher - For order management
 */
class OrdersDataFetcher extends BaseTableDataFetcher {
    constructor() {
        super('/api/orders');
    }

    async getOrders(params = {}) {
        return this.get('', params);
    }

    async getOrder(id) {
        return this.get(`/${id}`);
    }

    async createOrder(orderData) {
        return this.post('', orderData);
    }

    async updateOrder(id, orderData) {
        return this.put(`/${id}`, orderData);
    }

    async deleteOrder(id) {
        return this.delete(`/${id}`);
    }

    async updateOrderStatus(id, status) {
        return this.post(`/${id}/status`, { status });
    }
}

/**
 * Services Data Fetcher - For service management
 */
class ServicesDataFetcher extends BaseTableDataFetcher {
    constructor() {
        super('/api/services');
    }

    async getServices(params = {}) {
        return this.get('', params);
    }

    async getService(id) {
        return this.get(`/${id}`);
    }

    async createService(serviceData) {
        return this.post('', serviceData);
    }

    async updateService(id, serviceData) {
        return this.put(`/${id}`, serviceData);
    }

    async deleteService(id) {
        return this.delete(`/${id}`);
    }
}

/**
 * Data Fetcher Factory - Creates appropriate fetcher instances
 */
class DataFetcherFactory {
    static create(type) {
        switch (type) {
            case 'users':
                return new UsersDataFetcher();
            case 'roles':
                return new RolesDataFetcher();
            case 'permissions':
                return new PermissionsDataFetcher();
            case 'orders':
                return new OrdersDataFetcher();
            case 'services':
                return new ServicesDataFetcher();
            default:
                throw new Error(`Unknown data fetcher type: ${type}`);
        }
    }
}

// Export for global use
window.BaseTableDataFetcher = BaseTableDataFetcher;
window.UsersDataFetcher = UsersDataFetcher;
window.RolesDataFetcher = RolesDataFetcher;
window.PermissionsDataFetcher = PermissionsDataFetcher;
window.OrdersDataFetcher = OrdersDataFetcher;
window.ServicesDataFetcher = ServicesDataFetcher;
window.DataFetcherFactory = DataFetcherFactory;
