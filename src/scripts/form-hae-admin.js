function autoSize(element) {
  element.style.height = "4rem";

  return (element.style.height = element.scrollHeight + "px");
}

function setComp(element, id) {
  const comp = document.querySelector(`#${id}`);

  if (element.type === "date") {
    const selectDay = new Date(element.value);
    const mm = selectDay.getMonth() + 1;
    const dd = selectDay.getUTCDate();
    return (comp.innerHTML = dd + "/" + mm);
  }

  return (comp.innerHTML = element.value);
}

function handleSubmit(event) {
  event.preventDefault();
  const inputs = document.querySelectorAll("input");
  const select = document.querySelector("select");
  const textarea = document.querySelector("textarea");
  const warnText = document.querySelector(".error-form");
  const form = document.querySelector("form");
  let isError = false;
  const fields = [...inputs, select, textarea];

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

  form.submit();
}
