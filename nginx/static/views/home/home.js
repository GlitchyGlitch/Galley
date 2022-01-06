import View from "/modules/view.js";
import Thumbnail from "/components/thumbnail/thumbnail.js";
import Lightbox from "/components/lightbox/lightbox.js";
import { showScrollbar, hideScrollbar } from "../../modules/style.js";

export default View({
  name: "home",
  mainFunc(root, { api }) {
    const showLightbox = async (id) => {
      hideScrollbar();
      const lightbox = Lightbox.new();
      const photo = await api.fetchPhotoByID(id);
      lightbox.fill({ src: photo.path });
      const lightboxNode = lightbox.render();
      lightboxNode.querySelector(".overlay").addEventListener("click", (e) => {
        root.removeChild(e.target.parentNode);
        showScrollbar();
      });
      root.appendChild(lightboxNode);
    };

    const renderThubnails = async () => {
      const wrapper = root.querySelector("#photo-wrapper");
      const photos = await api.fetchPhotos();

      for (const photo of photos) {
        let thumbnail = Thumbnail.new();
        let date = new Date(photo.created_at);
        date = `${date.getDay()}.${date.getMonth()}.${date.getFullYear()}`;
        thumbnail.fill({ src: photo.path, date: date });
        const thumbnailNode = thumbnail.render();
        thumbnailNode
          .querySelector(".overlay")
          .addEventListener("click", () => {
            console.log(photo.id);
            showLightbox(photo.id);
          });
        wrapper.appendChild(thumbnailNode);
      }
    };

    renderThubnails();
  },
});
