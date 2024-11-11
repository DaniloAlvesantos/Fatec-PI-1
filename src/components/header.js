class HeaderFatec extends HTMLElement {
  constructor() {
    super();

    this.build();
  }

  build() {
    const shadow = this.attachShadow({ mode: "open" });
    shadow.appendChild(this.styles());

    const button = this.createButton();
    const userIcon = this.craeteUserICon();

    userIcon.appendChild(button);
    shadow.appendChild(userIcon);
  }

  craeteUserICon() {
    const header = document.createElement("header");
    const userContainer = document.createElement("div");
    const imageContainer = document.createElement("div");
    const image = document.createElement("img");
    const userName = document.createElement("p");

    const cargo = window.state.user.cargo;
    const name = window.state.user.name;

    header.classList = "header";
    userContainer.classList = "user-container";
    imageContainer.classList = "user";
    image.src = "../public/icons/user.svg";
    image.alt = "user icon";
    userName.innerHTML = `${cargo} ${name}`;

    imageContainer.appendChild(image);
    userContainer.appendChild(imageContainer);
    userContainer.appendChild(userName);
    header.appendChild(userContainer);
    return header;
  }

  createButton() {
    const link = document.createElement("a");
    link.href = this.dataset.buttonHref;

    const button = document.createElement("button");
    button.classList = "button-primary-outline";
    button.setAttribute("style", "--button-border: var(--fatec-red-500)");
    button.innerHTML = this.dataset.buttonTitle;

    link.appendChild(button);

    return link;
  }

  logOut() {
    window.deleteState();
  }

  styles() {
    const style = document.createElement("style");
    style.textContent = `
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 1rem;
        }

        .user-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;

            font-family: "Roboto", sans-serif;
            font-weight: 500;
        }

        .user {
          padding: 0.5rem;
          border-radius: 100%;
          box-shadow: 0 4px 4px 2px #00000035;
        }

        @media (min-width: 768px) {
          .user {
            padding: 0.8rem;
          }
        }
        
        .button-primary-outline {
            background: none;
            color: var(--fatec-dark-500);

            padding: 0.6rem 1.5rem;

            border: 2px solid var(--button-border);
            border-radius: 8px;

            font-family: "Roboto", sans-serif;
            font-weight: 500;

            cursor: pointer;

            transition: background ease 400ms;
        }

        .button-primary-outline:hover {
            color: var(--fatec-white-500);
            background: var(--button-border);
        }       
     `;

    return style;
  }
}

customElements.define("header-fatec", HeaderFatec);
