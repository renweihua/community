const TokenKey = 'Authorization'

export function getToken() {
    var token = localStorage.getItem(TokenKey);
    if (token) return 'Bearer ' + token;
    else return token;
}

export function setToken(token) {
    return localStorage.setItem(TokenKey, token);
}

export function removeToken() {
    return localStorage.removeItem(TokenKey);
}
