class CookieManager {
  setJWT(value, days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `jwt=${value}; expires=${date.toUTCString()}; Secure; SameSite=Strict; path=/;`;
  }
  getUserID() {
    return JSON.parse(atob(this.getJWT().split(".")[1])).sub;
  }
  isLogged() {
    if (!this.getJWT()) {
      return false;
    }
    return true;
  }
  getJWT() {
    try {
      const parameterArray = document.cookie.split(";");
      const jwtParam = parameterArray.filter((str) => str.includes("jwt="))[0];
      const jwt = jwtParam.split("=")[1];
      return jwt;
    } catch (_) {
      return;
    }
  }
  unsetJWT() {
    document.cookie = "jwt=; Max-Age=0;";
  }
}
export default CookieManager;
