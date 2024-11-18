function showPassword(checkBox) {
  const eye = document.querySelector(".eye");
  const inp = document.querySelector("#password");

  eye.src =
    checkBox.checked === true
      ? "./public/icons/eye-off.svg"
      : "./public/icons/eye.svg";
  inp.type = checkBox.checked === true ? "text" : "password";
}

async function handleSubmit(event) {
  event.preventDefault();
  const inputs = document.querySelectorAll("input");
  const errorField = document.querySelector("#error");

  if (!inputs[0].value.length || !inputs[1].value.length) {
    return (errorField.innerHTML = "Preencha os campos!!");
  }

  errorField.innerHTML = "";

  const user = await login(inputs[0].value, inputs[1].value);
  if (!user) return;

  if (!window.state) {
    window.state = {};
  }

  window.state.user = {
    name: user.name,
    email: user.email,
    cargo: user.cargo,
  };

  if (typeof window.saveState === "function") {
    window.saveState();
  }

  window.location.href =
    window.state.user.cargo.toLocaleLowerCase() !== "professor"
      ? location.href.replace("index.html", "pages/admin/painel.admin.html")
      : location.href.replace("index.html", "pages/home.html");
}

async function login(email, senha) {
  const db = await fetch("../../db.json")
    .then((response) => response.json())
    .then((data) => data.users);
  const users = db;
  let currentUser;
  const errorField = document.querySelector("#error");

  users.forEach((user) => {
    if (email === user.email && senha === user.senha) {
      currentUser = user;
      return;
    } else if (email === user.email && senha !== user.senha) {
      errorField.innerHTML = "Senha incorreta";
      return;
    }
  });

  if (!currentUser) {
    errorField.innerHTML = "Usuario não encontrado!";
    return null;
  }

  errorField.style.display = "none";

  return currentUser;
}
