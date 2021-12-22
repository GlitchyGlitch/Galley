import View from "/modules/view.js";

export default View({
  name: "home",
  async mainFunc(root, { api }) {
    const wrapper = root.querySelector("#photo-wrapper");
    const photos = await api.fetchPhotos();
    const cards = [];
    for (const photo of photos) {
      console.log(photo);
      const el = document.createElement("div");
      const inner = document.createElement("img");
      inner.src = photo.path;
      inner.classList.add("w-100", "h-100");
      inner.style.objectFit = "cover";
      el.appendChild(inner);
      el.classList.add("col-12", "col-md-6", "col-xl-4", "py-3", "px-md-3");
      el.style.height = "240px";
      cards.push(el);
    }
    cards.map((card) => wrapper.appendChild(card)); //TODO: Make it appear at once
  },
});
