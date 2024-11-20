function autoSize(element) {
  element.style.height = "4rem";

  return (element.style.height = element.scrollHeight + "px");
}

function handleFile(element) {
  const fileField = document.querySelector("#fileField");
  const container = document.querySelector("#file-container");
  const message = document.querySelector("#file-message");
  const icon = document.querySelector("#icon-file");
  const img = document.createElement("iframe");
  fileField.appendChild(message);
  const itHasImg = document.querySelector("iframe");

  if (element.files[0].type !== "application/pdf") {
    !!itHasImg ? document.querySelector("iframe").remove() : null; // img.remove() não funcionou por algum motivo...
    icon.style.display = "block";
    element.value = "";
    return (message.innerHTML = "Apenas arquivos PDF");
  }

  !!itHasImg && document.querySelector("iframe").remove();

  icon.style.display = "none";
  img.src = URL.createObjectURL(element.files[0]);
  img.classList = "iframe";
  img.onload = () => {
    URL.revokeObjectURL(img.src);
  };

  container.appendChild(img);
  return (message.innerHTML = element.files[0].name);
}

function handleSubmit(event) {
  event.preventDefault();
  const fileInput = document.querySelector("#documento");
  const textareas = document.querySelectorAll("textarea");
  const warnText = document.querySelector(".error-form");
  let isError = false;
  let fields = [...textareas, fileInput];

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

  const formData = new FormData();

  formData.append("aproveitamento", textareas[0].value);
  formData.append("resultados", textareas[1].value);
  formData.append("anotacoes", textareas[2].value);
  formData.append("arquivo", fileInput.files[0]);
}

function handleDownload(url) {
  const a = document.createElement("a");
  a.href = url;
  a.download = url.split("/").pop();
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}
