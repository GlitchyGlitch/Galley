import View from "/modules/view.js";
import Thumbnail from "/components/thumbnail/thumbnail.js";
import Lightbox from "/components/lightbox/lightbox.js";
import Comment from "/components/comment/comment.js";

import { convertDate } from "/modules/utils.js";
import { showScrollbar, hideScrollbar } from "/modules/style.js";

export default View({
  name: "my-photos",
  async mainFunc(root, { api, cookieManager }) {
    if (!cookieManager.getJWT()) {
      window.location.replace("/");
    }
    const photos = await api.fetchPhtotosByUserID(cookieManager.getUserID());
    const showLightbox = async (id) => {
      hideScrollbar();
      const photo = await api.fetchPhotoByID(id);
      const comments = await api.fetchCommentsByPhotoID(id);
      const commentsComponents = await Promise.all(
        comments.map(async (comment) => {
          const user = await api.fetchUserByID(comment.owner_id);
          const commentComponent = Comment.new();

          commentComponent.fill({
            content: comment.content,
            firstName: user.first_name,
            lastName: user.last_name,
          });

          return commentComponent;
        })
      );

      const lightboxComponent = Lightbox.new();
      lightboxComponent.fill({
        src: photo.path,
        comments: commentsComponents,
      });
      const lightboxNode = lightboxComponent.render();
      lightboxNode
        .querySelector("#exit")
        .addEventListener("click", hideLightbox);
      lightboxNode
        .querySelector("#background")
        .addEventListener("click", hideLightbox);

      root.appendChild(lightboxNode);
    };

    const hideLightbox = () => {
      root.removeChild(root.querySelector(".lightbox"));
      showScrollbar();
    };
    const renderThubnails = async () => {
      const wrapper = root.querySelector("#photo-wrapper");
      const photos = await api.fetchPhotos();

      for (const photo of photos) {
        let thumbnail = Thumbnail.new();
        thumbnail.fill({
          src: photo.path,
          date: convertDate(photo.created_at),
        });
        const thumbnailNode = thumbnail.render();
        thumbnailNode
          .querySelector(".overlay")
          .addEventListener("click", () => {
            showLightbox(photo.id);
          });

        wrapper.appendChild(thumbnailNode);
      }
    };

    renderThubnails();
  },
});