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
  const inputs = document.querySelectorAll("input");
  const errorField = document.querySelector("#error");

  if (!inputs[0].value.length || !inputs[1].value.length) {
    return (errorField.innerHTML = "Preencha os campos!!");
  }

  errorField.innerHTML = "";

  window.state.user.email = inputs[0].value;
  window.saveState();

  window.location.href =
    window.state.user.cargo.toLocaleLowerCase() !== "professor"
      ? location.href.replace("index.html", "pages/admin/painel.admin.html")
      : location.href.replace("index.html", "pages/home.html");
}
