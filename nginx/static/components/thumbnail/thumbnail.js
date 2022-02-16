import Component from "/modules/component.js";
import { convertDate } from "/modules/utils.js";
import { showLightbox } from "/components/lightbox/lightbox.js";

const Thumbnail = new Component({
  name: "thumbnail",
});

const renderThumbnail = (root, api, cookieManager, photo) => {
  let thumbnailComponent = Thumbnail.new();
  thumbnailComponent.fill({
    src: photo.path,
    date: convertDate(photo.created_at),
  });
  const thumbnailNode = thumbnailComponent.render();
  thumbnailNode.querySelector(".overlay").addEventListener("click", () => {
    showLightbox(root, api, cookieManager, photo.id);
  });
  return thumbnailNode;
};

const renderThumbnails = async (root, api, cookieManager) => {
  const photos = await api.fetchPhotos();
  const thumbnailNodes = [];
  for (const photo of photos) {
    thumbnailNodes.push(renderThumbnail(root, api, cookieManager, photo));
  }
  return thumbnailNodes;
};

export { Thumbnail, renderThumbnails, renderThumbnail };
