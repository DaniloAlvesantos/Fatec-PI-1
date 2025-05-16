function showPassword(checkBox) {
  const eye = document.querySelector(".eye");
  const inp = document.querySelector("#password");

  eye.src =
    checkBox.checked === true
      ? "./public/icons/eye-off.svg"
      : "./public/icons/eye.svg";
  inp.type = checkBox.checked === true ? "text" : "password";
}

function handleSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const email = document.querySelector("#email").value;
  const password = document.querySelector("#password").value;
  const errorField = document.querySelector("#error");

  if (!email || !password) {
    errorField.innerHTML = "Preencha os campos!!";
    return;
  }

  errorField.innerHTML = "";
  form.submit();
}
