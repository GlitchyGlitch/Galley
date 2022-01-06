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

  fill = (values) => {
    let html = this.template;
    for (const value in values) {
      let val =
        values[value] instanceof Component ? values[value].html : values[value];
      if (isComponentArray(val)) {
        val = val.reduce(
          (accumulator, current) => accumulator.html + current.html
        ).html;
      }
      html = html.replace(RegExp("{{" + value + "}}", "g"), val);
    }
    this.html = html;
  };

  render = () => {
    return document.createRange().createContextualFragment(this.html);
  };

  new = () => {
    return new Component(this.options, this.template);
  };
}

export default Component;
