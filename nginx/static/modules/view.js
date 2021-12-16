import { joinPaths } from "/modules/path.js";

const createView = (options) => {
  const c = new View(options);
  return c;
};

/**
 * @description
 * Main view class
 *
 * @param {Object} options
 * @param {String} options.htmlFile       Main HTML file for view
 * @param {String} options.viewsDir       Directory with views
 * @param {String} options.name           Name of view
 * @param {String} options.routerViewport Router's viewport
 * @param {String} options.mainFunc       Main callback for view. Executed after http file is loaded to router's viewport. Takes root node as argument.
 */
class View {
  node = null;
  constructor(options) {
    this.options = options;
    this.name = this.options.name || "main";
    this.htmlFile =
      this.options.htmlFile ||
      joinPaths([
        this.options.viewsDir || "views",
        this.options.name || "",
        `${this.options.name}.html`,
      ]);
  }

  async init() {
    await this.fetchNode();
    this.options.routerViewport.addEventListener("DOMNodeInserted", () => {
      this.options.mainFunc(this.node); // FIXME: check if it makes sense
    });
    return this.node;
  }

  fetchNode = async () => {
    // TODO: cache
    const req = new Request(this.htmlFile, {
      method: "GET",
    });

    let response = await fetch(req);
    response = await response.text();
    const element = document.createElement("div");
    element.innerHTML = response;
    this.node = element;
  };

  routerViewport = (vp) => {
    this.options.routerViewport = vp;
  };
}

export default createView;
