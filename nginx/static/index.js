"use strict";
// TODO: Make some sort of single sorurce of truth
import Router from "/modules/router.js";
import RegisterView from "/views/register/register.js";
import LoginView from "/views/login/login.js";
import HomeView from "/views/home/home.js";
import MyPhotosView from "/views/my-photos/my-photos.js";
import API from "/modules/api.js";
import CookieManager from "/modules/cookie-manager.js";

const renderButtons = (logged) => {
  const wrapper = document.querySelector("#button-wrapper");
  const wrapperLogged = document.querySelector("#button-wrapper-logged");
  const fab = document.querySelector("#fab");

  if (logged) {
    wrapperLogged.classList.remove("d-none");
    fab.classList.remove("d-none");
    return;
  }
  wrapper.classList.remove("d-none");
};

const main = () => {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  const routerViewport = document.querySelector("[router-view]");
  const router = new Router({ viewport: routerViewport });
  const cookieManager = new CookieManager();
  const api = new API({
    cookieManager,
  });
  if (!cookieManager.isLogged()) {
    renderButtons(false);
  } else {
    renderButtons(true);
  }

  router
    .add(/logout/, async () => {
      cookieManager.unsetJWT();
      window.location.replace("/");
    })
    .add(/login/, async () => {
      LoginView.routerViewport(routerViewport);
      await LoginView.init({ api, cookieManager });
      router.renderViewport(LoginView.node);
    })
    .add(/my-photos/, async () => {
      MyPhotosView.routerViewport(routerViewport);
      await MyPhotosView.init({ api, cookieManager });
      router.renderViewport(MyPhotosView.node);
    })
    .add(/register/, async () => {
      RegisterView.routerViewport(routerViewport);
      await RegisterView.init({ api, cookieManager });
      router.renderViewport(RegisterView.node);
    })
    .add("", async () => {
      HomeView.routerViewport(routerViewport);
      await HomeView.init({ api, cookieManager });
      router.renderViewport(HomeView.node);
    });
};
document.addEventListener("DOMContentLoaded", main);
