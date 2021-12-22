import View from "/modules/view.js";

export default View({
  name: "register",
  mainFunc(root, { cookieManager }) {
    if (cookieManager.getJWT()) {
      window.location.replace("/");
    }

    const nextView = () => {
      formViewport.classList.toggle("overflow-hidden", "overflow-scroll");
      formViewport.scrollBy(formViewport.offsetWidth, 0);
      formViewport.classList.toggle("overflow-hidden", "overflow-scroll");
    };
    const header = root.querySelector("#header");
    const nextButton = root.querySelector("#next");
    const signButton = root.querySelector("#sign");
    const formViewport = root.querySelector("#viewport");

    nextButton.addEventListener("click", () => {
      nextView();
    });
    signButton.addEventListener("click", () => {
      nextView();
      header.classList.toggle("opacity-0");
    });
  },
});
