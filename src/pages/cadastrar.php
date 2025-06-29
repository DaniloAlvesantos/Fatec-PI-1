<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../styles/global.css" />
    <link rel="stylesheet" href="../styles/components.css" />
    <style>
        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;

            width: 100%;
            height: 100%;
        }

        input {
            margin-bottom: 1rem;
            font-family: "Roboto", sans-serif;
        }

        label {
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            font-family: "Roboto Slab", sans-serif;
        }

        select {
            width: 11rem;
        }
    </style>
</head>

<body>
    <section>
        <main>
            <form method="POST" action="../server/controller/register.php">
                <label>Nome</label>
                <input class="input-secondary" name="name" type="text" placeholder="Nome" />
                <label>RG</label>
                <input class="input-secondary" name="RG" type="text" placeholder="000.000.000-00" />
                <label>Email</label>
                <input class="input-secondary" name="email" type="email" placeholder="E-mail" />
                <label>Matricula</label>
                <input class="input-secondary" name="matricula" type="number" placeholder="Matricula" />
                <label>turno</label>
                <input class="input-secondary" name="turno" type="text" placeholder="DTH" />
                <label>Senha</label>
                <input class="input-secondary" name="senha" type="password" placeholder="Senha" />
                <label>Confirme a senha</label>
                <input class="input-secondary" name="confirmar_senha" type="password" placeholder="Confirmar senha" />
                <label>Cargo</label>
                <select class="input-secondary" name="cargo">
                    <option>Professor</option>
                    <option>Coordenador</option>
                    <option>Secretaria</option>
                    <option>Diretor</option>
                </select>
                <label>Atribui a outras fatecs ?</label>
                <input class="input-secondary" type="checkbox" placeholder="Outras fatec" name="outras_fatecs" />
                <label>Selecione o curso</label>
                <select class="input-secondary" name="curso">
                    <option>DSM</option>
                    <option>GTD</option>
                    <option>GEP</option>
                </select>

                <button type="submit" class="button-primary"
                    style="
                --button-color: var(--fatec-red-500);
                --button-color-hover: var(--fatec-red-400);
                margin:2rem 0;">Enviar</button>
            </form>
        </main>
    </section>
</body>

</html>