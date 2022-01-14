import Component from "/modules/component.js";

const Comment = new Component({
  name: "comment",
});

const getCommentsByPhotoID = async (api, photoID) => {
  const comments = await api.fetchCommentsByPhotoID(photoID);
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
  return commentsComponents;
};
export { Comment, getCommentsByPhotoID };
