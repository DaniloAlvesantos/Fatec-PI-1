class HeaderFatec extends HTMLElement {
  constructor() {
    super();
    this.isAdmin = this.checkAdminStatus();
    this.build();
  }

  build() {
    const shadow = this.attachShadow({ mode: "open" });
    shadow.appendChild(this.styles());

    const header = document.createElement("header");
    header.classList = "header";

    const menuToggleButton = this.createMenuToggleButton();
    header.appendChild(menuToggleButton);

    const userSection = this.createHeaderUserSection();
    header.appendChild(userSection);

    const buttonSection = this.createButton();
    header.appendChild(buttonSection);

    shadow.appendChild(header);

    const asideMenu = this.createAsideMenu();
    shadow.appendChild(asideMenu);

    const menuOverlay = this.createMenuOverlay();
    shadow.appendChild(menuOverlay);

    this.setupMenuListeners(
      menuToggleButton,
      asideMenu,
      menuOverlay,
      asideMenu.querySelector(".close-menu-button")
    );
  }

  checkAdminStatus() {
    const currentPath = window.location.pathname;
    const isAdminPath = currentPath.includes("/admin/");
    const userRole = window.state?.user?.cargo;

    return isAdminPath || userRole === "Admin" || userRole === "Administrador";
  }

  getUserMenuItems() {
    const basePath = this.getBasePath();

    return [
      { title: "Home", href: `${basePath}/pages/home.php`, icon: "🏠" },
      { title: "Edital", href: `${basePath}/pages/edital.php`, icon: "📋" },
      { title: "Vagas", href: `${basePath}/pages/haes.php`, icon: "💼" },
      {
        title: "Minhas Inscrições",
        href: `${basePath}/pages/me/inscricoes.php`,
        icon: "📝",
      },
      {
        title: "Relatórios",
        href: `${basePath}/pages/me/relatorios.php`,
        icon: "📊",
      },
    ];
  }

  getAdminMenuItems() {
    const basePath = this.getBasePath();

    return [
      {
        title: "Painel Admin",
        href: `${basePath}/pages/admin/painel.admin.php`,
        icon: "🎛️",
      },
      {
        title: "Editais",
        href: `${basePath}/pages/admin/editals.admin.php`,
        icon: "📋",
      },
      {
        title: "HAEs",
        href: `${basePath}/pages/admin/haes.admin.php`,
        icon: "💼",
      },
      {
        title: "Inscrições",
        href: `${basePath}/pages/admin/inscricoes.admin.php`,
        icon: "📝",
      },
      {
        title: "Relatórios",
        href: `${basePath}/pages/admin/relatorios.admin.php`,
        icon: "📊",
      },
      {
        title: "Adicionar Edital",
        href: `${basePath}/pages/admin/add/editais.admin.php`,
        icon: "➕",
      },
      {
        title: "Adicionar HAE",
        href: `${basePath}/pages/admin/add/hae_form.admin.php`,
        icon: "💼",
      },
      {
        title: "Cadastrar Usuário",
        href: `${basePath}/pages/admin/dev/cadastro.php`,
        icon: "👤",
      },
    ];
  }

  createMenuToggleButton() {
    const toggleButton = document.createElement("div");
    toggleButton.classList = "menu-toggle";
    toggleButton.id = "menu-toggle";

    toggleButton.innerHTML = `
      <div class="hamburger"></div>
      <div class="hamburger"></div>
      <div class="hamburger"></div>
    `;
    return toggleButton;
  }

  createHeaderUserSection() {
    const userContainer = document.createElement("div");
    const imageContainer = document.createElement("div");
    const image = document.createElement("img");
    const userName = document.createElement("p");

    const cargo =
      window.state && window.state.user && window.state.user.cargo
        ? window.state.user.cargo
        : "Guest";
    const name =
      window.state && window.state.user && window.state.user.name
        ? window.state.user.name.split(" ")[0]
        : "User";

    const src = `${this.getBasePath()}/public/icons/user.svg`;

    userContainer.classList = "user-container";
    imageContainer.classList = "user";
    image.src = src;
    image.alt = "user icon";
    userName.innerHTML = `${cargo} ${name}`;

    imageContainer.appendChild(image);
    userContainer.appendChild(imageContainer);
    userContainer.appendChild(userName);

    return userContainer;
  }

  createButton() {
    const link = document.createElement("a");
    link.href = this.dataset.buttonHref || "#";

    const button = document.createElement("button");
    button.classList = "button-primary-outline";
    button.setAttribute("style", `--button-border: var(--fatec-red-500)`);
    button.innerHTML = this.dataset.buttonTitle || "Action";

    if (this.dataset.buttonTitle === "Sair") {
      button.addEventListener("click", async (e) => {
        e.preventDefault();
        await this.logOut();
      });
    }

    link.appendChild(button);
    return link;
  }

  async logOut() {
    const basePath = this.getBasePath();

    const route = `${basePath}/server/controller/logout.php`;

    try {
      await fetch(route, {
        method: "GET",
      });

      window.location.href = `${basePath}/index.php`;
    } catch (error) {
      console.error("Erro ao fazer logout:", error);
    }
  }

  createAsideMenu() {
    const aside = document.createElement("aside");
    aside.classList = "aside-menu";
    aside.id = "aside-menu";

    const closeButton = document.createElement("button");
    closeButton.classList = "close-menu-button";
    closeButton.innerHTML = "&times;";
    aside.appendChild(closeButton);

    const nav = document.createElement("nav");
    const ul = document.createElement("ul");

    const menuItems = this.isAdmin
      ? this.getAdminMenuItems()
      : this.getUserMenuItems();

    console.log(this.isAdmin);

    menuItems.forEach((item) => {
      const li = document.createElement("li");
      const a = document.createElement("a");
      a.href = item.href;
      a.innerHTML = `${item.icon} ${item.title}`;
      a.classList.add("menu-item");
      li.appendChild(a);
      ul.appendChild(li);
    });

    nav.appendChild(ul);
    aside.appendChild(nav);

    return aside;
  }

  createMenuOverlay() {
    const overlay = document.createElement("div");
    overlay.classList = "menu-overlay";
    overlay.id = "menu-overlay";
    return overlay;
  }

  setupMenuListeners(toggleButton, asideMenu, overlay, closeButton) {
    toggleButton.addEventListener("click", () => {
      asideMenu.classList.add("open");
      overlay.classList.add("active");
      document.body.style.overflow = "hidden";
    });

    const closeMenu = () => {
      asideMenu.classList.remove("open");
      overlay.classList.remove("active");
      document.body.style.overflow = "";
    };

    closeButton.addEventListener("click", closeMenu);
    overlay.addEventListener("click", closeMenu);

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && asideMenu.classList.contains("open")) {
        closeMenu();
      }
    });
  }

  /**
   * Derives the base path for the application, typically up to and including '/src'.
   * This helps in constructing absolute paths for resources and navigation.
   * @returns {string} The base path (e.g., "/my-app/src" or "/src").
   */
  getBasePath() {
    const currentPath = window.location.pathname;
    const pathParts = currentPath.split("/");
    const srcIndex = pathParts.findIndex((part) => part === "src");

    if (srcIndex !== -1) {
      return pathParts.slice(0, srcIndex + 1).join("/");
    }

    return "/src";
  }

  styles() {
    const style = document.createElement("style");
    style.textContent = `
        /* General variables (assuming these are defined globally or via other means) */
        :host {
  --fatec-red-500: #b20000;
  --fatec-red-700: #7e0000;
  --fatec-white-500: #ffffff;
  --fatec-dark-500: #0d0d0d;
  --fatec-gray-500: #666666;
  --fatec-gray-300: #dadada;
  --fatec-blue-500: #005c6d;
  --fatec-blue-700: #004854;
  --fatec-lightblue-500: #00c1cf;
  --fatec-lightblue-600: #00d8e8;
  /*  Media Query */
  --sm: 640px;
  --md: 768px;
  --lg: 1024px;
  --xl: 1280px;
  --2xl: 1536px;
  /* Font Sizes */
  --text-xs-font-size: 0.75rem;
  --text-sm-font-size: 0.875rem;
  --text-base-font-size: 1rem;
  --text-lg-font-size: 1.125rem;
  --text-xl-font-size: 1.25rem;
  --text-2xl-font-size: 1.5rem;
  /* Line Heights */
  --text-xs-line-height: 1rem;
  --text-sm-line-height: 1.25rem;
  --text-base-line-height: 1.5rem;
  --text-lg-line-height: 1.75rem;
  --text-xl-line-height: 1.75rem;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Spreads items evenly */
            padding: 1rem;
            background-color: var(--fatec-white-500); /* Added background for clarity */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Subtle shadow */
            position: relative; /* For z-index context with overlay */
            z-index: 1000; /* Ensure header is above other content */
        }

        .user-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: "Inter", sans-serif; /* Changed to Inter as per instructions */
            font-weight: 500;
            color: var(--fatec-dark-500);
        }

        .user {
          padding: 0.5rem;
          border-radius: 100%;
          box-shadow: 0 4px 4px 2px #00000035;
          display: flex; /* For centering image */
          align-items: center;
          justify-content: center;
          background-color: #f7f7f7; /* Light background for user icon area */
        }

        .user img {
            width: 24px; /* Adjust size as needed */
            height: 24px;
            display: block;
        }

        @media (min-width: 768px) {
          .user {
            padding: 0.8rem;
          }
          .user img {
            width: 32px;
            height: 32px;
          }
        }
        
        .button-primary-outline {
            background: none;
            color: var(--fatec-dark-500);
            padding: 0.6rem 1.5rem;
            border: 2px solid var(--button-border);
            border-radius: 8px;
            font-family: "Inter", sans-serif; /* Changed to Inter */
            font-weight: 500;
            cursor: pointer;
            transition: background ease 400ms, color ease 400ms;
        }

        .button-primary-outline:hover {
            color: var(--fatec-white-500);
            background: var(--button-border);
        }

        /* Menu Toggle Button (Hamburger) */
        .menu-toggle {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            width: 30px;
            height: 25px;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu-toggle:hover {
            background-color: rgba(0,0,0,0.05);
        }

        .hamburger {
            width: 100%;
            height: 3px;
            background-color: var(--fatec-dark-500);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Aside Menu */
        .aside-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px; /* Adjust width as needed */
            max-width: 80vw; /* Responsive width */
            height: 100%;
            background-color: #f8f8f8; /* Light background for menu */
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
            transform: translateX(-100%); /* Start off-screen */
            transition: transform 0.3s ease-in-out;
            z-index: 1001; /* Above header and overlay */
            padding: 20px;
            box-sizing: border-box; /* Include padding in width calculation */
            display: flex;
            flex-direction: column;
        }

        .aside-menu.open {
            transform: translateX(0); /* Slide in */
        }

        .aside-menu .close-menu-button {
            align-self: flex-end; /* Pushes close button to the right */
            background: none;
            border: none;
            font-size: 2rem;
            color: var(--fatec-dark-500);
            cursor: pointer;
            padding: 0;
            margin-bottom: 20px;
        }

        .aside-menu nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .aside-menu nav li {
            margin-bottom: 15px;
        }

        .aside-menu nav a {
            text-decoration: none;
            color: var(--fatec-dark-500);
            font-family: "Inter", sans-serif;
            font-weight: 500;
            font-size: 1.1rem;
            display: block;
            padding: 10px 5px;
            border-radius: 5px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .aside-menu nav a:hover {
            background-color: var(--fatec-red-500);
            color: var(--fatec-white-500);
        }

        /* Menu Overlay */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1000; /* Below aside menu, above header */
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
     `;

    return style;
  }
}

customElements.define("header-fatec", HeaderFatec);
