"use strict";

import Router from "/modules/router.js";
import RegisterView from "/views/register/register.js";
import LoginView from "/views/login/login.js";
import HomeView from "/views/home/home.js";
import API from "/modules/api.js";
import CookieManager from "/modules/cookie-manager.js";

const main = () => {
  const routerViewport = document.querySelector("[router-view]");
  const router = new Router({ viewport: routerViewport });
  const api = new API();
  const cookieManager = new CookieManager();

  router
    .add(/login/, async () => {
      LoginView.routerViewport(routerViewport);
      await LoginView.init({ api, cookieManager });
      router.renderViewport(LoginView.node);
    })
    .add(/register/, async () => {
      RegisterView.routerViewport(routerViewport);
      await RegisterView.init({ api });
      router.renderViewport(RegisterView.node);
    })
    .add("", async () => {
      HomeView.routerViewport(routerViewport);
      await HomeView.init({ api });
      router.renderViewport(HomeView.node);
    });
};
document.addEventListener("DOMContentLoaded", main);
