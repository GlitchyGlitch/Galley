const hideScrollbar = () => {
  document.children[0].style.overflow = "hidden";
};
const showScrollbar = () => {
  document.children[0].style.overflow = "auto";
};

export { hideScrollbar, showScrollbar };
