import { joinPaths } from "/modules/path.js";

/**
 * @description
 * Main API connection point
 *
 * @param {Object} options
 * @param {String} options.apiRoot    Root directory for API
 * @param {String} options.apiVersion API version
 */
class API {
  apiRoot = "api";
  apiVersion = "v1";
  constructor(options) {
    this.options = options || {};
    this.apiRoot = this.options.apiRoot || this.apiRoot;
    this.apiVersion = this.options.apiVersion || this.apiVersion;
    this.apiPath = joinPaths(["/", this.apiRoot, this.apiVersion]);
  }

  fetchPhotos = async () => {
    const path = joinPaths([this.apiPath, "photos"]);
    const req = new Request(path, {
      method: "GET",
      cache: "default",
    });
    const resp = await fetch(req);
    return resp.json();
  };
}

export default API; // TODO: Do proper configuration of requests
