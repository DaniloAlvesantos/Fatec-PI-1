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

  window.location.href = location.href.replace("index.html", "pages/home.html");
}