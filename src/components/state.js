const savedState = JSON.parse(localStorage.getItem("state"));

window.state = JSON.parse(localStorage.getItem("state"));

const saveState = () => {
  return localStorage.setItem("state", JSON.stringify(window.state));
};

const deleteState = () => {
  return localStorage.removeItem("state");
};

window.saveState = saveState;
window.deleteState = deleteState;
