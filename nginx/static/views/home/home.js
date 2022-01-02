import View from "/modules/view.js";
import Thumbnail from "/components/thumbnail/thumbnail.js";

export default View({
  name: "home",
  async mainFunc(root, { api }) {
    const wrapper = root.querySelector("#photo-wrapper");
    const photos = await api.fetchPhotos();
    const cards = [];
    for (const photo of photos) {
      let thumbnail = Thumbnail.new();
      let date = new Date(photo.created_at);
      console.log(date.getYear());
      date = `${date.getDay()}.${date.getMonth()}.${date.getFullYear()}`;
      thumbnail.fill({ src: photo.path, date: date });
      console.log(
        "🚀 ~ file: home.js ~ line 13 ~ mainFunc ~ photo.createdAt",
        photo.createdAt
      );
      cards.push(thumbnail.render());
    }
    cards.map((card) => wrapper.appendChild(card)); //TODO: Make it appear at once
  },
});
