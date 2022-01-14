import View from "/modules/view.js";

import { renderThumbnails } from "/components/thumbnail/thumbnail.js";

export default View({
  name: "my-photos",
  async mainFunc(root, { api, cookieManager }) {
    const wrapper = root.querySelector("#photo-wrapper");
    const thumbnailNodes = await renderThumbnails(root, api, cookieManager);
    thumbnailNodes.map((n) => wrapper.appendChild(n));
  },
});
