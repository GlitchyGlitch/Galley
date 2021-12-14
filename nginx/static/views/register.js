const main = () => {
  document
    .querySelector("[router-view]")
    .addEventListener("DOMNodeInserted", () => {
      const nextButton = document.getElementById("next");
      const formViewport = document.getElementById("viewport");
      nextButton.addEventListener("click", () => {
        formViewport.classList.remove("overflow-hidden");
        formViewport.classList.add("overflow-scroll");
        formViewport.scrollBy(formViewport.offsetWidth, 0);
      });
    });
};

export default main;
