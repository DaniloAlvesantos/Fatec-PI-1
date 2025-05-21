<?php
session_start();

if (isset($_SESSION['user'])) {
  if ($_SESSION["user"]["cargo"] !== "Professor") {
    header("Location:" . dirname($_SERVER['PHP_SELF'], 2) . "/src/pages/admin/painel.admin.php");
    exit();
  }
  header("Location:" . dirname($_SERVER['PHP_SELF'], 2) . "/src/pages/home.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once __DIR__ . "/server/model/Docente.php";
  $errorMessage = "";
  $docente = new Docente();

  $email = $_POST["email"];
  $senha = $_POST["password"];

  $user = $docente->login($email, $senha);

  if ($user) {
    $_SESSION["user"] = $user;
    if ($user["cargo"] !== "Professor") {
      header("Location:" . dirname($_SERVER['PHP_SELF'], 2) . "/src/pages/admin/painel.admin.php");
      exit();
    }

    header("Location:" . dirname($_SERVER['PHP_SELF'], 2) . "/src/pages/home.php");
    exit();
  } else {
    $errorMessage = "Email ou senha inválidos.";
  }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | HAE Fatec Itapira</title>
  <link rel="stylesheet" href="./styles/global.css" />
  <link rel="stylesheet" href="./styles/landing-page.css" />
  <link rel="stylesheet" href="./styles/components.css" />
</head>

<body>
  <main>
    <nav>
      <img
        src="./public/fatec-itapira.png"
        alt="logo fatec"
        class="logo-fatec" />
      <button
        class="button-primary slide-left"
        style="
            --button-color: var(--fatec-red-500);
            --button-color-hover: var(--fatec-red-400);
          ">
        Cadastrar
      </button>
    </nav>

    <form class="card-glass" onsubmit="handleSubmit(event)" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div>
        <span>
          <label for="email">Email</label>
          <input
            class="input-secondary"
            type="email"
            placeholder="Digite seu email"
            aria-required="true"
            autocomplete="email"
            id="email"
            name="email"
            required />
        </span>

        <span class="container-relative">
          <label for="password">Senha</label>
          <input
            class="input-secondary"
            type="password"
            placeholder="Digite sua senha"
            aria-required="true"
            id="password"
            name="password" />
          <label for="showPass">
            <img src="./public/icons/eye.svg" alt="icone" class="eye" />
            <input type="checkbox" id="showPass" onclick="showPassword(this)" hidden />
          </label>
          <a href="#"> Esqueceu a senha ? </a>
        </span>

        <button
          type="submit"
          class="button-secondary"
          style="
              --button-color: var(--fatec-red-500);
              --button-color-hover: var(--fatec-red-400);
            ">
          Entrar
        </button>

        <p id="error"><?php echo $errorMessage ?? ""; ?></p>
      </div>
    </form>
  </main>

  <script src="scripts/landing-page.js" type="text/javascript"></script>
</body>

</html>