<body>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <?php
    include_once("globals.php");
    include_once("models/usuario.php");
    include_once("models/message.php");
    include_once("dao/usuarioDao.php");
    include_once("templates/header.php");
    include_once("array_dados.php");

    include_once("models/pagination.php");

    //Instanciando a classe
    $usuario = new userDAO($conn, $BASE_URL);
    $QtdTotalUser = new userDAO($conn, $BASE_URL);

    // METODO DE BUSCA DE PAGINACAO
    $pesquisa_user = filter_input(INPUT_GET, 'pesquisa_user');
    $ativo_user = filter_input(INPUT_GET, 'ativo_user');

    $condicoes = [
        strlen($pesquisa_user) ? 'usuario_user LIKE "%' . $pesquisa_user . '%"' : null,
        strlen($ativo_user) ? 'ativo_user = "' . $ativo_user . '"' : null
    ];
    $condicoes = array_filter($condicoes);

    // REMOVE POSICOES VAZIAS DO FILTRO
    $where = implode(' AND ', $condicoes);

    // QUANTIDADE usuarioS
    $qtdUserItens1 = $QtdTotalUser->QtdUsuario($where);

    $qtdUserItens = ($qtdUserItens1['0']);
    // PAGINACAO
    $obPagination = new pagination($qtdUserItens, $_GET['pag'] ?? 1, 10);
    $obLimite = $obPagination->getLimit();

    ?>

    <!--tabela usuario-->
    <div class="container py-2">

        <div class="row" style="background-color: #d3d3d3">
            <form class="formulario" id="form_pesquisa" method="GET">
                <div class="form-group row">
                    <h6 class="page-title" style="margin-top:10px">Selecione itens para efetuar Pesquisa</h6>
                    <input type="hidden" name="pesquisa" id="pesquisa" value="sim">
                    <div class="form-group col-sm-2">
                        <input type="text" name="pesquisa_user" style="margin-top:10px; border:0rem" id="pesquisa_user" placeholder="Pesquisa por usuário">
                    </div>

                    <div class="form-group col-sm-1">
                        <input type="radio" value="s" name="ativo_user" id="ativo" placeholder="Pesquisa por usuario">
                        <label for="ativo_user">Ativo</label><br>
                        <input type="radio" style="margin-top:-5px" name="ativo_user" value="n" id="ativo_user" placeholder="Pesquisa por usuario">
                        <label for="ativo">Inativo</label><br>
                    </div>
                    <div class="form-group col-sm-1">
                        <button style="margin:10px; font-weight:600" type="submit" class="btn-sm btn-light">Pesquisar</button>
                    </div>
                </div>
            </form>

            <?php
            $order = null;
            // PREENCHIMENTO DO FORMULARIO COM QUERY
            $query = $usuario->selectAllusuario($where, $order, $obLimite);

            // GETS 
            unset($_GET['pag']);
            unset($_GET['pg']);
            $gets = http_build_query($_GET);

            // PAGINACAO
            $paginacao = '';
            $paginas = $obPagination->getPages();

            foreach ($paginas as $pagina) {

                $class = $pagina['atual'] ? 'btn-primary' : 'btn-light';
                $paginacao .= '<a href="?pag=' . $pagina['pag'] . '&' . $gets . '"> 
                <button type="button" class="btn ' . $class . '">' . $pagina['pag'] . '</button>
                </a>';
            };

            ?>

        </div>
        <div>
            <h4 class="page-title">Relação de Usuários</h4>
        </div>
        <table class="table table-sm table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Senha</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">Nível</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($query as $usuario) :
                    extract($usuario);
                ?>
                    <tr>
                        <td scope="row" class="col-id"><?= $id_usuario ?></td>
                        <td scope="row" class="nome-coluna-table"><?= $usuario_user ?></td>
                        <td scope="row" class="nome-coluna-table"><?= $senha_user ?></td>
                        <td scope="row" class="nome-coluna-table"><?= $email_user ?></td>
                        <td scope="row" class="nome-coluna-table"><?= $ativo_user ?></td>
                        <td scope="row" class="nome-coluna-table"><?= $nivel_user ?></td>

                        <td class="action">
                            <!-- <a href="cad_usuario.php"><i name="type" value="create" style="color:green; margin-right:10px" class="bi bi-plus-square-fill edit-icon"></i></a> -->
                            <a href="<?= $BASE_URL ?>show_usuario.php?id_usuario=<?= $id_usuario ?>"><i style="color:green; margin-right:10px" class="fas fa-eye check-icon"></i></a>

                            <a href="<?= $BASE_URL ?>edit_usuario.php?id_usuario=<?= $id_usuario ?>"><i style="color:blue" name="type" value="edite" class="aparecer-acoes far fa-edit edit-icon"></i></a>

                            <a href="<?= $BASE_URL ?>show_usuario.php?id_usuario=<?= $id_usuario ?>"><i style="color:red; margin-left:10px" name="type" value="edite" class="d-inline-block bi bi-x-square-fill delete-icon"></i></a>

                            <div id="info"></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <?php
            "<div style=margin-left:20px;>";
            echo "<div style='color:blue; margin-left:20px;'>";
            echo "</div>";
            echo "<nav aria-label='Page navigation example'>";
            echo " <ul class='pagination'>";
            echo " <li class='page-item'><a class='page-link' href='list_usuario.php?pg=1&" . $gets . "''><span aria-hidden='true'>&laquo;</span></a></li>"; ?>
            <?= $paginacao ?>
            <?php echo "<li class='page-item'><a class='page-link' href='list_usuario.php?pg=$qtdUserItens&" . $gets . "''><span aria-hidden='true'>&raquo;</span></a></li>";
            echo " </ul>";
            echo "</nav>";
            echo "</div>"; ?>
            <hr>
        </div>
        <div id="id-confirmacao" class="btn_acoes oculto">
            <p>Deseja deletar este usuario: <?= $usuario_ant ?>?</p>
            <button class="btn btn-success styled" onclick=cancelar() type="button" id="cancelar" name="cancelar">Cancelar</button>
            <button class="btn btn-danger styled" onclick=deletar() value="default" type="button" id="deletar-btn" name="deletar">Deletar</button>
        </div>
    </div>

    <?php

    //modo cadastro
    $formData = "0";
    $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if ($formData !== "0") {
        $_SESSION['msg'] = "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
        //header("Location: index.php");
    } else {
        echo "<p style='color: #f00;'>Erro: Usuário não cadastrado!</p>";
    };

    try {

        $query_Total = $conn->prepare($sql_Total);
        $query_Total->execute();

        $query_result = $query_Total->fetchAll(PDO::FETCH_ASSOC);

        # conta quantos registros tem no banco de dados
        $query_count = $query_Total->rowCount();

        # calcula o total de paginas a serem exibidas
        $qtdPag = ceil($query_count / $limite);
    } catch (PDOexception $error_Total) {

        echo 'Erro ao retornar os Dados. ' . $error_Total->getMessage();
    }
    echo "<div style=margin-left:20px;>";
    echo "<div style='color:blue; margin-left:20px;'>";
    echo "</div>";
    echo "<nav aria-label='Page navigation example'>";
    echo " <ul class='pagination'>";
    echo " <li class='page-item'><a class='page-link' href='list_usuario.php?pg=1'><span aria-hidden='true'>&laquo;</span></a></li>";

    if ($qtdPag > 1 && $pg <= $qtdPag) {
        for ($i = 1; $i <= $qtdPag; $i++) {
            if ($i == $pg) {
                echo "<li class='page-item active'><a class='page-link' class='ativo'>" . $i . "</a></li>";
            } else {
                echo "<li class='page-item '><a class='page-link' href='list_usuario.php?pg=$i'>" . $i . "</a></li>";
            }
        }
    }
    echo "<li class='page-item'><a class='page-link' href='list_usuario.php?pg=$qtdPag'><span aria-hidden='true'>&raquo;</span></a></li>";
    echo "</ul>";
    echo "</nav>";
    echo "</div>"; ?>
    <div>
        <hr>
        <a class="btn btn-success styled" style="margin-left:120px" href="cad_usuario.php">Novo usuário</a>
    </div>
</body>

<script>
    function apareceOpcoes() {
        $('#deletar-btn').val('nao');
        let mudancaStatus = ($('#deletar-btn').val())
        console.log(mudancaStatus);
        let idAcoes = (document.getElementById('id-confirmacao'));
        idAcoes.style.display = 'block';
    }

    function deletar() {
        $('#deletar-btn').val('ok');
        let idAcoes = (document.getElementById('id-confirmacao'));
        idAcoes.style.display = 'none';
        let mudancaStatus = ($('#deletar-btn').val())
        console.log(mudancaStatus);
        window.location = "<?= $BASE_URL ?>del_usuario.php?id_usuario=<?= $id_usuario ?>";
    };

    function cancelar() {
        let idAcoes = (document.getElementById('id-confirmacao'));
        idAcoes.style.display = 'none';
        console.log("chegou no cancelar");

    };
    src = "https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js";
</script>