<?php
if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];

    echo "<script>
      window.state = {};
      window.state.user = {
        id: '{$user['id_docente']}',
        name: '{$user['nome']}',
        email: '{$user['email']}',
        rg: '{$user['RG']}',
        matricula: '{$user['matricula']}',
        turno: '{$user['turno']}',
        cargo: '{$user['cargo']}',
        curso: '{$user['curso']}',
        outrasFatecs: " . ($user['outras_fatecs'] ? 'true' : 'false') . "
      };
    </script>";
}
