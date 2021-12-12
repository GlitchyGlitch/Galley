"use strict";

import Router from "./router.js";

const fetchView = async (viewPath) => {
  const req = new Request(viewPath, {
    method: "GET",
  });

  let response = await fetch(req);
  response = await response.text();
  const element = document.createElement("div");
  element.innerHTML = response;
  return element;
};

const main = () => {
  const routerView = document.querySelector("[router-view]");

  const router = new Router();

  router
    .add(/login/, async () => {
      routerView.innerHTML = "";
      const view = await fetchView("views/login.html");
      routerView.append(view);
    })
    .add(/register/, async () => {
      routerView.innerHTML = "";
      const view = await fetchView("views/register.html");
      routerView.append(view);
    })
    .add("", async () => {
      routerView.innerHTML = "";
      const view = await fetchView("views/home.html");
      routerView.append(view);
    });
};
document.addEventListener("DOMContentLoaded", main);
