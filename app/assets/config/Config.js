
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

export const Config = {
    BASE_URL:BASE_URL,
    EMAIL_REGREX:/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/,
}

