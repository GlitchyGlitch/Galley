class Router {
  routes = [];

  root = "/";

  constructor(options) {
    if (options && options.root) this.root = options.root;
    this.listen();
  }

  add = (path, cb) => {
    this.routes.push({ path, cb });
    return this;
  };

  clearSlashes = (path) =>
    path.toString().replace(/\/$/, "").replace(/^\//, "");

  getFragment = () => {
    let fragment = "";
    fragment = this.clearSlashes(
      decodeURI(window.location.pathname + window.location.search)
    );
    fragment = fragment.replace(/\?(.*)$/, "");
    fragment = this.root !== "/" ? fragment.replace(this.root, "") : fragment;
    return this.clearSlashes(fragment);
  };

  navigate = (path = "") => {
    window.history.pushState(null, null, this.root + this.clearSlashes(path));
    return this;
  };

  listen = () => {
    clearInterval(this.timer);
    this.timer = setInterval(this.interval, 50);
  };

  interval = () => {
    if (this.current === this.getFragment()) return;
    this.current = this.getFragment();

    this.routes.some((route) => {
      const match = this.current.match(route.path);
      if (match) {
        match.shift();
        route.cb.apply({}, match);
        return match;
      }
      return false;
    });
  };
}

export default Router;
