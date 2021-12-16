import View from "/modules/view.js";

export default View({
  name: "register",
  mainFunc(root) {
    const nextView = () => {
      formViewport.classList.toggle("overflow-hidden");
      formViewport.classList.toggle("overflow-scroll");
      formViewport.scrollBy(formViewport.offsetWidth, 0);
      formViewport.classList.toggle("overflow-hidden");
      formViewport.classList.toggle("overflow-scroll");
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
