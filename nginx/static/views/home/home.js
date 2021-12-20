import View from "/modules/view.js";

export default View({
  name: "home",
  async mainFunc(root, { api }) {
    const wrapper = root.querySelector("#photo-wrapper");
    const photos = await api.fetchPhotos();
    const cards = [];
    for (const photo of photos) {
      const el = document.createElement("div");
      el.classList.add("col-4");
      el.classList.add("m-3");
      el.style.background = `url("${photo.path}?height=240") center center / cover`;
      el.style.height = "240px";
      cards.push(el);
    }
    wrapper.appendChild(...cards);
  },
});
