
import Cookies from 'js-cookie';

const TOKEN_KEY = 'authToken';

export default {
    setToken(token) {
        Cookies.set(TOKEN_KEY, token, { expires: 7, secure: true }); 
    },
    getToken() {
        return Cookies.get(TOKEN_KEY);
    },
    isAuthenticated() {
        return this.getToken() != null;
    },
    logout() {
        Cookies.remove(TOKEN_KEY);
    }
}
