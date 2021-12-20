import { joinPaths } from "/modules/path.js";

const createView = (options) => {
  const c = new View(options);
  return c;
};

/**
 * @description
 * Main view class.
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

  init = async (mainOptions) => {
    mainOptions = mainOptions || {};
    await this.fetchNode();
    const observer = new MutationObserver((mutations) => {
      for (const { type } of mutations)
        if (type === "childList") this.options.mainFunc(this.node, mainOptions);
    });

    observer.observe(this.options.routerViewport, { childList: true });
    return this.node;
  };

  fetchNode = async () => {
    // TODO: Do proper configuration of request
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
