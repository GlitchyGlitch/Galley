import View from "/modules/view.js";

export default View({
  name: "register",
  mainFunc(root, { cookieManager, api }) {
    if (cookieManager.isLogged()) {
      window.location.replace("/");
    }

    const header = root.querySelector("#header");
    const nextButton = root.querySelector("#next");
    const signButton = root.querySelector("#sign");
    const emailInput = root.querySelector("#email");
    const emailFeedback = root.querySelector("#email-feedback");
    const passwdInput = root.querySelector("#passwd");
    const confirmPasswdInput = root.querySelector("#passwd-confirm");
    const firstNameInput = root.querySelector("#first-name");
    const lastNameInput = root.querySelector("#last-name");
    const steps = root.querySelectorAll(".step");
    function validateName(name) {
      return /^[a-zA-ZąĄęńŃóÓĘśŚćĆźŹżŻ]{2,255}$/u.test(name);
    }
    const setView = (view) => {
      for (const step of steps) step.classList.add("d-none");
      steps[view].classList.remove("d-none");
    };

    nextButton.addEventListener("click", () => {
      if (!emailInput.checkValidity()) {
        emailInput.classList.add("is-invalid");
        return;
      }
      emailInput.classList.remove("is-invalid");

      if (passwdInput.value.length < 8) {
        passwdInput.classList.add("is-invalid");
        return;
      }
      passwdInput.classList.remove("is-invalid");

      if (passwdInput.value !== confirmPasswdInput.value) {
        passwdInput.classList.add("is-invalid");
        confirmPasswdInput.classList.add("is-invalid");
        return;
      }
      passwdInput.classList.remove("is-invalid");
      confirmPasswdInput.classList.remove("is-invalid");
      setView(1);
    });

    signButton.addEventListener("click", async () => {
      if (!validateName(firstNameInput.value)) {
        firstNameInput.classList.add("is-invalid");
        return;
      }
      firstNameInput.classList.remove("is-invalid");

      if (!validateName(lastNameInput.value)) {
        lastNameInput.classList.add("is-invalid");
        return;
      }
      lastNameInput.classList.remove("is-invalid");

      const resp = await api.register(
        emailInput.value,
        passwdInput.value,
        firstNameInput.value,
        lastNameInput.value
      );
      if (!resp) {
        setView(0);
        emailInput.classList.add("is-invalid");
        emailFeedback.classList.add("d-block");
        return;
      }
      setView(2);
      emailInput.classList.remove("is-invalid");
      emailFeedback.classList.add("d-block");
      header.classList.add("opacity-0");
    });
  },
});
