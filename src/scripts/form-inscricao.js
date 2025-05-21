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

function handleSubmit(e) {
  e.preventDefault();

  // Get all dias execucao inputs
  const diasExecucaoInputs = document.querySelectorAll("[id^=dia-execucao]");
  const diasExecucao = [];

  diasExecucaoInputs.forEach((input) => {
    if (input.value.trim() !== "") {
      diasExecucao.push(input.value.trim());
    }
  });

  // Collect form data
  const formData = new FormData(document.getElementById("form-subscription"));
  formData.append("dias_execucao", JSON.stringify(diasExecucao));

  // Show loading or disable submit button
  const submitButton = document.getElementById("submitButton");
  submitButton.disabled = true;
  submitButton.textContent = "Enviando...";

  // Send AJAX request
  fetch(window.location.href, {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        document.querySelector(".error-form").textContent = data.message;
        document.querySelector(".error-form").style.color = "green";
      } else {
        // Show error message
        document.querySelector(".error-form").textContent = data.message;
        document.querySelector(".error-form").style.color = "red";
        submitButton.disabled = false;
        submitButton.textContent = "Enviar";
      }
    });
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
