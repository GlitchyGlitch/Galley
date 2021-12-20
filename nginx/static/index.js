"use strict";

import Router from "/modules/router.js";
import RegisterView from "/views/register/register.js";
import LoginView from "/views/login/login.js";
import HomeView from "/views/home/home.js";
import API from "/modules/api.js";

const main = () => {
  const routerViewport = document.querySelector("[router-view]");
  const router = new Router({ viewport: routerViewport });
  const api = new API();

  router
    .add(/login/, async () => {
      LoginView.routerViewport(routerViewport);
      await LoginView.init();
      router.renderViewport(LoginView.node);
    })
    .add(/register/, async () => {
      RegisterView.routerViewport(routerViewport);
      await RegisterView.init();
      router.renderViewport(RegisterView.node);
    })
    .add("", async () => {
      HomeView.routerViewport(routerViewport);
      await HomeView.init({ api });
      router.renderViewport(HomeView.node);
    });
};
document.addEventListener("DOMContentLoaded", main);
