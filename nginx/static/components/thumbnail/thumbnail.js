import Component from "/modules/component.js";
import { convertDate } from "/modules/utils.js";
import { showLightbox } from "/components/lightbox/lightbox.js";

const Thumbnail = new Component({
  name: "thumbnail",
});

const renderThumbnails = async (root, api, cookieManager) => {
  const photos = await api.fetchPhotos();
  const thumbnailNodes = [];
  for (const photo of photos) {
    let thumbnailComponent = Thumbnail.new();
    thumbnailComponent.fill({
      src: photo.path,
      date: convertDate(photo.created_at),
    });
    const thumbnailNode = thumbnailComponent.render();
    thumbnailNode.querySelector(".overlay").addEventListener("click", () => {
      showLightbox(root, api, cookieManager, photo.id);
    });
    thumbnailNodes.push(thumbnailNode);
  }
  return thumbnailNodes;
};

export { Thumbnail, renderThumbnails };
