const savedState = JSON.parse(localStorage.getItem("state")) || {
  user: {
    name: "Júnior",
    cargo: "Professor",
    email: "",
  },
};

window.state = JSON.parse(localStorage.getItem("state")) || {
  user: {
    name: "Júnior",
    cargo: "Professor",
    email: "",
  },
};

const saveState = () => {
  return localStorage.setItem("state", JSON.stringify(window.state));
};

const deleteState = () => {
  return localStorage.removeItem("state");
};

window.saveState = saveState;
window.deleteState = deleteState;
