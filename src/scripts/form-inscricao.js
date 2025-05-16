function addInput() {
  const container = document.querySelector("#container-inputs");
  const inputs = container.querySelectorAll("input");
  const button = document.querySelector("#addInputButton");

  if (inputs.length === 4) {
    return button.setAttribute("disabled", "");
  }

  const newInpt = inputs[0].cloneNode(true);
  newInpt.setAttribute("id", `dia-dia-execucao${inputs.length + 1}`);
  newInpt.setAttribute("name", `dia-dia-execucao${inputs.length + 1}`);
  newInpt.value = "";

  container.appendChild(newInpt);
}

function removeInput(input) {
  const container = document.querySelector("#container-inputs");
  const button = document.querySelector("#addInputButton");

  if (input.getAttribute("id") === "dia-execucao1") {
    return;
  }

  if (
    container.querySelectorAll("input").length < 4 &&
    button.hasAttribute("disabled") === true
  ) {
    button.removeAttribute("disabled");
  }

  container.removeChild(input);
}

async function handleSubmit(event) {
  event.preventDefault();

  const form = document.querySelector("#form-subscription");
  const inputs = form.querySelectorAll("input");
  const execDays = form.querySelectorAll("input[name='dia-execucao']");
  const textareas = form.querySelectorAll("textarea");
  const fields = [...inputs, ...textareas];

  const warnText = document.querySelector(".error-form");
  let isError = false;

  fields.forEach((item) => {
    if (!item.value || item.value === "") {
      isError = true;
      return (warnText.innerHTML = "Preenchar os campos vazios");
    } else {
      if (isError === true) {
        return (warnText.innerHTML = "Preenchar os campos vazios");
      }
      isError = false;
      return (warnText.innerHTML = "");
    }
  });

  if (isError === true) {
    return;
  }

  let days = [];

  execDays.forEach((input) => {
    days.push(input.value);
  });

  form.submit();
}

function verifyExecDay(input) {
  if (input.value.length < 15) return;

  const error = document.querySelector(".error");
  const days = ["SEG", "TER", "QUA", "QUI", "SEX", "SÁB"];
  const turn = ["M", "T", "N"];
  let subString = input.value;

  if (!subString.includes(",")) {
    subString = subString.replace(/\s/g, ",");
  }

  subString = subString.replace(/\s/g, "");
  subString = subString.split(",");

  subString = `${subString[0].substr(0, 3)},${subString[1].charAt(0)},${
    subString[2]
  }`.toUpperCase();

  const subTeste = subString.split(",");

  if (!days.includes(subTeste[0])) {
    return (error.innerHTML = "Digite um dia valido");
  } else if (!turn.includes(subTeste[1])) {
    return (error.innerHTML = "Digite um turno valido");
  }

  error.innerHTML = "";

  input.value = subString;
}

function autoSize(element) {
  element.style.height = "4rem";

  return (element.style.height = element.scrollHeight + "px");
}
