// ajax.js

const Ajax = (function () {
    // Function to make a generic request
    const request = async (url, options = {}) => {
        const response = await fetch(url, options);

        // Create a unified response structure
        const contentType = response.headers.get('Content-Type');
        let responseData;

        if (contentType && contentType.includes('application/json')) {
            responseData = await response.json();
        } else {
            responseData = await response.text();
        }

        // Return response and status for external handling
        return {
            ok: response.ok,
            status: response.status,
            statusText: response.statusText,
            data: responseData,
        };
    };

    // Function to handle GET requests
    const get = (url, params = {}, headers = {}) => {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = queryString ? `${url}?${queryString}` : url;

        return request(fullUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
        });
    };

    // Function to handle POST requests
    const post = (url, body = {}, headers = {}) => {
        let options = {
            method: 'POST',
            headers: {
                ...headers,
            },
        };

        // Check if body is FormData
        if (body instanceof FormData) {
            options.body = body; // FormData is directly assignable to the body
            // Do not set 'Content-Type'; fetch sets it automatically for FormData
        } else {
            options.body = JSON.stringify(body);
            options.headers['Content-Type'] = 'application/json';
        }

        return request(url, options);
    };

    // Function to handle PUT requests
    const put = (url, body = {}, headers = {}) => {
        return request(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
            body: JSON.stringify(body),
        });
    };

    // Function to handle DELETE requests
    const del = (url, headers = {}) => {
        return request(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
        });
    };

    // Download (handles blob response and filename)
    const download = async (url, method = 'GET', headers = {}) => {
        const response = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/octet-stream',
                ...headers,
            },
        });

        if (!response.ok) {
            return {
                ok: false,
                status: response.status,
                statusText: response.statusText,
                data: null,
            };
        }

        const blob = await response.blob();
        const contentDisposition = response.headers.get('Content-Disposition');
        const filenameMatch = contentDisposition?.match(/filename="?([^"]+)"?/);
        const filename = filenameMatch?.[1] || 'download';

        return {
            ok: true,
            status: response.status,
            data: blob,
            filename,
        };
    };

    // Return the public API of the module
    return {
        get,
        post,
        put,
        delete: del,
        request, // For custom HTTP methods and advanced options
        download,
    };
})();

// Export the module to be used in other files
export default Ajax;
