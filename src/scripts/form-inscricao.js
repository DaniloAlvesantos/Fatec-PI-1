function addInput() {
  const container = document.querySelector("#container-inputs");
  const fields = document.querySelectorAll(".container-fields-execucao");
  const button = document.querySelector("#addInputButton");

  if (fields.length === 4) {
    return button.setAttribute("disabled", "");
  }

  const newField = fields[0].cloneNode(true);
  const fieldNumber = fields.length + 1;

  // Update the container ID
  newField.setAttribute("id", `dia-dia-execucao${fieldNumber}`);

  // Update all the name attributes for the new field
  const selects = newField.querySelectorAll("select");
  const inputs = newField.querySelectorAll("input[type='time']");

  selects[0].setAttribute("name", `dia-execucao${fieldNumber}`);
  selects[1].setAttribute("name", `turno-execucao${fieldNumber}`);
  inputs[0].setAttribute("name", `hora-inicio-execucao${fieldNumber}`);
  inputs[0].setAttribute("id", `hora-inicio-execucao${fieldNumber}`);
  inputs[1].setAttribute("name", `hora-fim-execucao${fieldNumber}`);
  inputs[1].setAttribute("id", `hora-fim-execucao${fieldNumber}`);

  // Clear the values
  selects[0].selectedIndex = 0;
  selects[1].selectedIndex = 0;
  inputs[0].value = "";
  inputs[1].value = "";

  container.appendChild(newField);
}

function removeInput(input) {
  const container = document.querySelector("#container-inputs");
  const button = document.querySelector("#addInputButton");

  if (input.getAttribute("id") === "dia-dia-execucao1") {
    return;
  }

  const fields = container.querySelectorAll(".container-fields-execucao");
  if (fields.length <= 4 && button.hasAttribute("disabled")) {
    button.removeAttribute("disabled");
  }

  container.removeChild(input);
}

function handleSubmit(e) {
  e.preventDefault();

  const execucaoFields = document.querySelectorAll(
    ".container-fields-execucao"
  );
  const diasExecucao = [];

  execucaoFields.forEach((field, index) => {
    const fieldNumber = index + 1;
    const dia = field.querySelector(
      `select[name="dia-execucao${fieldNumber}"]`
    );
    const turno = field.querySelector(
      `select[name="turno-execucao${fieldNumber}"]`
    );
    const horaInicio = field.querySelector(
      `input[name="hora-inicio-execucao${fieldNumber}"]`
    );
    const horaFim = field.querySelector(
      `input[name="hora-fim-execucao${fieldNumber}"]`
    );

    // Only add if all fields are filled
    if (
      dia &&
      turno &&
      horaInicio &&
      horaFim &&
      dia.value &&
      turno.value &&
      horaInicio.value &&
      horaFim.value
    ) {
      // Convert time format from HH:MM to HH
      const inicioHora = horaInicio.value.split(":")[0];
      const fimHora = horaFim.value.split(":")[0];

      // Format as "DIA,TURNO,HORA_INICIO-HORA_FIM"
      const execucaoString = `${dia.value},${turno.value},${inicioHora}-${fimHora}`;
      diasExecucao.push(execucaoString);
    }
  });

  const formData = new FormData(document.getElementById("form-subscription"));
  formData.append("dias_execucao", JSON.stringify(diasExecucao));

  const submitButton = document.getElementById("submitButton");
  submitButton.disabled = true;
  submitButton.textContent = "Enviando...";

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
        document.querySelector(".error-form").textContent = data.message;
        document.querySelector(".error-form").style.color = "red";
        submitButton.disabled = false;
        submitButton.textContent = "Enviar";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      document.querySelector(".error-form").textContent =
        "Erro ao enviar formulário";
      document.querySelector(".error-form").style.color = "red";
      submitButton.disabled = false;
      submitButton.textContent = "Enviar";
    });
}

function validateTimeRange(startTimeInput, endTimeInput) {
  const startTime = startTimeInput.value;
  const endTime = endTimeInput.value;

  if (startTime && endTime) {
    const start = new Date(`2000-01-01T${startTime}`);
    const end = new Date(`2000-01-01T${endTime}`);

    if (start >= end) {
      alert("A hora de início deve ser anterior à hora de fim");
      endTimeInput.focus();
      return false;
    }
  }
  return true;
}

function autoSize(element) {
  element.style.height = "4rem";

  return (element.style.height = element.scrollHeight + "px");
}

document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector("#container-inputs");

  // Add validation to existing and new time inputs
  container.addEventListener("change", function (e) {
    if (e.target.type === "time") {
      const fieldContainer = e.target.closest(".container-fields-execucao");
      const startInput = fieldContainer.querySelector(
        'input[name*="hora-inicio"]'
      );
      const endInput = fieldContainer.querySelector('input[name*="hora-fim"]');

      if (startInput.value && endInput.value) {
        validateTimeRange(startInput, endInput);
      }
    }
  });
});
