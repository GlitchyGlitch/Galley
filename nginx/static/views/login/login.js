import View from "/modules/view.js";

export default View({
  name: "login",
  async mainFunc(root, { api, cookieManager }) {
    //TODO: ADD validation
    if (cookieManager.getJWT()) {
      window.location.replace("/");
    }
    root.querySelector("#form").addEventListener("submit", async () => {
      const email = root.querySelector("#email");
      const passwd = root.querySelector("#passwd");
      const feedback = root.querySelector("#feedback");
      const resp = await api.login(email.value, passwd.value);
      if (!resp) {
        email.classList.add("is-invalid");
        passwd.classList.add("is-invalid");
        feedback.classList.add("d-block");
      } else {
        email.classList.remove("is-invalid");
        passwd.classList.remove("is-invalid");
        feedback.classList.add("d-none");
        email.classList.add("is-valid");
        passwd.classList.add("is-valid");
        cookieManager.setJWT(resp.jwt);
        window.location.replace("/");
      }
    });
  },
});
