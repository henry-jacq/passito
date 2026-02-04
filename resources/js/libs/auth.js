const Auth = (() => {
    const tokenKey = 'passito_auth_token';

    const setToken = (token) => {
        if (!token) return;
        localStorage.setItem(tokenKey, token);
    };

    const getToken = () => {
        return localStorage.getItem(tokenKey);
    };

    const clearToken = () => {
        localStorage.removeItem(tokenKey);
    };

    const withAuthHeaders = (headers = {}) => {
        const token = getToken();
        if (!token) {
            return headers;
        }

        return {
            ...headers,
            Authorization: `Bearer ${token}`,
        };
    };

    return {
        setToken,
        getToken,
        clearToken,
        withAuthHeaders,
    };
})();

export default Auth;
