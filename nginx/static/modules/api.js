import { joinPaths } from "/modules/utils.js";

/**
 * @description
 * Main API connection point
 *
 * @param {Object} options
 * @param {String} options.apiRoot    Root directory for API
 * @param {String} options.apiVersion API version
 * @param {String} options.cookieManager API version
 */
class API {
  apiRoot = "api";
  apiVersion = "v1";
  constructor(options) {
    this.options = options || {};
    this.apiRoot = this.options.apiRoot || this.apiRoot;
    this.apiVersion = this.options.apiVersion || this.apiVersion;
    this.apiPath = joinPaths(["/", this.apiRoot, this.apiVersion]);
    this.cookieManager = this.options.cookieManager;
  }

  fetchUserByID = async (id) => {
    const path = joinPaths([this.apiPath, "users", id]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };

  fetchPhtotosByUserID = async (id) => {
    const path = joinPaths([this.apiPath, "users", id, "photos"]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };

  fetchPhotos = async () => {
    const path = joinPaths([this.apiPath, "photos"]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };

  fetchCommentsByPhotoID = async (id) => {
    const path = joinPaths([this.apiPath, "photos", id, "comments"]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };

  fetchPhotoByID = async (id) => {
    const path = joinPaths([this.apiPath, "photos", id]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };

  postCommentByPhotoID = async (id, content) => {
    const path = joinPaths([this.apiPath, "photos", id, "comments"]);
    const jwt = this.cookieManager.getJWT();
    const headers = {
      "Contnet-Type": "application/json;charset=UTF-8",
    };
    if (jwt) {
      headers.Authorization = `Bearer ${jwt}`;
    }
    const req = new Request(path, {
      method: "POST",
      headers: headers,
      body: JSON.stringify({
        content,
      }),
    });
    const resp = await fetch(req);
    return resp.json();
  };

  postPhoto = async (mime, data) => {
    //TODO: check at least extension of file

    const path = joinPaths([this.apiPath, "photos"]);
    const jwt = this.cookieManager.getJWT();
    const headers = {
      "Contnet-Type": "application/json;charset=UTF-8",
    };
    if (jwt) {
      headers.Authorization = `Bearer ${jwt}`;
    }
    const req = new Request(path, {
      method: "POST",
      headers: headers,
      body: JSON.stringify({
        base64_img: data,
        mime,
      }),
    });
    const resp = await fetch(req);
    return resp.json();
  };

  login = async (email, password) => {
    let resp = null;
    const path = joinPaths([this.apiPath, "login"]);
    const req = new Request(path, {
      method: "POST",
      body: JSON.stringify({ email, password }),
    });
    resp = await fetch(req);
    if (!resp.ok) {
      return;
    }
    return resp.json();
  };

  register = async (email, password, firstName, lastName) => {
    let resp = null;
    const path = joinPaths([this.apiPath, "users"]);
    const req = new Request(path, {
      method: "POST",
      body: JSON.stringify({
        email,
        password,
        first_name: firstName,
        last_name: lastName,
        role: "regular",
      }),
    });
    resp = await fetch(req);
    if (!resp.ok) {
      return;
    }
    return resp.json();
  };
}

export default API; // TODO: Do proper configuration of requests
