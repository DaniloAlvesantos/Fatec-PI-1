class RodapeSP extends HTMLElement {
  constructor() {
    super();

    this.build();
  }

  build() {
    const shadow = this.attachShadow({ mode: "open" });
    const rodape = this.createRodape();

    shadow.appendChild(rodape);
  }

  createRodape() {
    const container = document.createElement("footer");
    const component = `
    <link
      rel="stylesheet"
      type="text/css"
      href="https://www.saopaulo.sp.gov.br/barra-govsp/css/rodape-padrao-govsp.min.css"
    />
    <section id="govsp-rodape">
    <div class="container">
        <div class="linha-botoes">
        <div class="coluna-4">
            <a
            href="https://www.ouvidoria.sp.gov.br/Portal/Default.aspx"
            class="btn btn-model"
            >Ouvidoria</a
            >
        </div>

        <div class="coluna-4">
            <a href="http://www.transparencia.sp.gov.br/" class="btn btn-model"
            >Transparência</a
            >
        </div>

        <div class="coluna-4">
            <a href="http://www.sic.sp.gov.br/" class="btn btn-model">SIC</a>
        </div>
        </div>
    </div>

    <div class="container rodape">
        <div class="logo-rodape">
        <a href="https://www.saopaulo.sp.gov.br/">
            <img
            src="https://www.saopaulo.sp.gov.br/barra-govsp/img/logo-rodape-governo-do-estado-sp.png"
            alt="site do Governo de São Paulo"
            width="206"
            height="38"
            />
        </a>
        </div>
    </div>
    </section>`;
    container.innerHTML = component;

    return container;
  }
}

customElements.define("rodape-sp", RodapeSP);
