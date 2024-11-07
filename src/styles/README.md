# Guia de Estilos

## Veja as funções e qualidade de código

### Components.css

#### Solução

Criar estilos que seram reaproveitados, por isso o nome de componentes, onde irá conter os componentes de nossa aplicação.

#### Funções

###### Cores dinâmicas

Em alguns componentes existe a possíbilidade de colocar cores dinâmicas, que variam. 

```css
.button-primary {
    background: var(--button-color);
    /* outros estilos */
}
```

_--button-color_ não existe no _:root_ do projeto, para adicionar uma cor precisamos fazer o seguinte:

```html
<button class="button-primary-outline" style="--button-border: [variável/cor];">Sair</button>
```

#### Estilos

- Header
    - User Container
        - User
- Button 
    - Primary
    - Secondary
- Button Outline
    - Primary
    - Secondary
- Input
    - Primary
    - Secondary
- Textarea
- Card
    - Primary
    - Secondary

##### Header

É o cabeçalho principal, onde fica o componente de usuário e botão de saída e navegação. Sendo **user-container** > **user**, os componentes de usuário.

```html
<header class="header">
    <div class="user-container">
    <div class="user">
        <img src="../public/icons/user.svg" alt="" />
    </div>
    <p>Professor Júnior</p>
    </div>
    <a href="#">
    <button class="button-primary-outline" style="--button-border: var(--fatec-red-500)">
        Sair
    </button>
    </a>
</header>
```

#### Button

É o componente botão onde existe 2 estilos, _primary_ e _secondary_.

```html
<button class="button-primary" style="--button-color: var(--fatec-red-500); --button-color-hover: var(--fatec-red-400);">
    Visualizar
</button>
```

#### Button Outline

É o componente botão porem sem fundo, apenas com bordar e demais estilos.

```html
<button class="button-primary-outline" style="--button-border: var(--fatec-red-500)">
    Visualizar
</button>
```

#### Input

É o componente de entrada de formulários com 2 estilos, _primary_ e _secondary_.

```html
<input
  class="input-primary"
  type="text"
  id="teste"
  name="teste"
  placeholder="Teste de input"
/>
```

#### Textarea

É o componente de entrada de textos, porem com estilos adicionais.

```html
<textarea
   oninput="autoSize(this)"
   class="textarea-primary"
   type="text"
   id="teste"
   name="teste"
   placeholder="Testando textarea"
></textarea>
```

Função _autoSize_ é a função para enquanto estiver escrevendo no campo ele irá ajustando a altura automaticamente.

#### Card

Componente de _containers_ para exibição de informações, etc. Contendo 2 estilos também! _primary_ e _secondary_.


***card-primary***
```html
<div class="card-primary">
    <div class="card-primary-banner" style="--gradient-1: #db3746; --gradient-2: #ff4c4d">
        <img src="../public/icons/job.svg" alt="" />
    </div>

    <span>
        <h2>Vagas</h2>
        <p>Confira as vagas disponiveis na unidade de Itapira</p>
    </span>

    <a href="./haes.html">
        <button class="button-secondary" style=" --button-color: var(--fatec-matte-red-500); --button-color-hover: var(--fatec-matte-red-400);">
            Visualizar
        </button>
    </a>
</div>
```

***card-secondary***
```html
<div class="card-secondary">
    <h2>Inscrições</h2>
    <p>Visualize HAEs inscritas</p>
</div>
```