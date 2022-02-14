import { isComponentArray } from "./utils.js";

/**
 * @description
 * Main component class.
 *
 * @param {Object} options
 * @param {String} options.name   Name of component
 * @param {String} options.vlaues Values that will be injected to templates
 */
class Component {
  constructor(options, template = "") {
    this.html = "";
    this.options = options;
    this.htmlFile = `/components/${options.name}/${options.name}.html`;
    if (!template) {
      this.loadTemplate();
    } else {
      this.template = template;
    }
  }

  loadTemplate = async () => {
    const req = new Request(this.htmlFile, {
      method: "GET",
    });

    let response = await fetch(req);
    response = await response.text();
    this.template = response;
  };

  fill = (fill) => {
    let html = this.template;
    for (const marker in fill) {
      let value =
        fill[marker] instanceof Component ? fill[marker].html : fill[marker];
      if (isComponentArray(value)) {
        let result = "";
        for (const v of value) {
          result += v.html;
        }
        value = result;
      }
      html = html.replace(RegExp("{{" + marker + "}}", "g"), value);
    }
    this.html = html;
  };

  render = () => {
    if (this.html === "") {
      this.fill();
    }
    return document
      .createRange()
      .createContextualFragment(this.html)
      .querySelector(`#${this.options.name.toLowerCase()}`);
  };

  new = () => {
    return new Component(this.options, this.template);
  };
}

export default Component;
