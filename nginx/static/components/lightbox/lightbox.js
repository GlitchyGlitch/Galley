import { Comment, getCommentsByPhotoID } from "/components/comment/comment.js";
import Component from "/modules/component.js";

const Lightbox = new Component({
  name: "lightbox",
});

const initCommentInput = (lightboxNode, api, photoID, logged) => {
  //TODO: validation of input
  const form = lightboxNode.querySelector("#comment-form");
  const input = lightboxNode.querySelector("#comment-input");
  const button = lightboxNode.querySelector("#comment-button");
  const comments = lightboxNode.querySelector("#comment-wrapper");
  if (logged) {
    form.classList.remove("d-none");
  }
  button.addEventListener("click", async () => {
    if (input.value.trim() === "") {
      return;
    }
    const comment = await api.postCommentByPhotoID(photoID, input.value);
    const user = await api.fetchUserByID(comment.owner_id);

    const commentComponent = Comment.new();
    commentComponent.fill({
      firstName: user.first_name,
      lastName: user.last_name,
      content: comment.content,
    });

    comments.appendChild(commentComponent.render());
    input.value = "";
  });
};

const hideScrollbar = () => {
  document.children[0].style.overflow = "hidden";
};
const showScrollbar = () => {
  document.children[0].style.overflow = "auto";
};

const showLightbox = async (root, api, cookieManager, photoID) => {
  hideScrollbar();

  const photo = await api.fetchPhotoByID(photoID);
  const commentsComponents = await getCommentsByPhotoID(api, photoID);

  const lightboxComponent = Lightbox.new();
  lightboxComponent.fill({
    src: photo.path,
    comments: commentsComponents,
  });
  const lightboxNode = lightboxComponent.render();

  lightboxNode.querySelectorAll("#exit, #background").forEach((node) =>
    node.addEventListener("click", () => {
      hideLightbox(root, lightboxNode);
      showScrollbar();
    })
  );

  root.appendChild(lightboxNode);
  initCommentInput(lightboxNode, api, photoID, cookieManager.isLogged());
};

const hideLightbox = (root, node) => {
  root.removeChild(node);
};

export { Lightbox, showLightbox };
