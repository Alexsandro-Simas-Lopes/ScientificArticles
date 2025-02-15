<?php
    require_once("../../artigos/model/artigodao.php");
    $receivedData = file_get_contents("php://input");
    $dataJSON = json_decode($receivedData);
    $artg = artigodao::getFindById($dataJSON->id);//Busca o ID do artigo
?>

<style>
    .modal-header-windown {
        border: none !important;
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 999999 !important;
        background-color: #ffffff !important;
        border-bottom: 2px solid #dcdfe5 !important;
    }

    .modal-body-windown {
        margin-top: 60px !important;
        margin-bottom: 60px !important;
        overflow-y: auto !important;
        max-height: calc(100vh - 120px) !important;
        background-color: #f1f3f7 !important;
        scroll-behavior: smooth !important;
    }

    .modal-footer-windown {
        border: none !important;
        position: fixed !important;
        bottom: 0 !important;
        width: 100% !important;
        z-index: 99999 !important;
        background-color: #ffffff !important;
        border-top: 2px solid #dcdfe5 !important;
    }

    .separte_buttons {
        display: flex;
        justify-content: space-between;
    }

    .ibox-content-windown {
        border-radius: 5px;
        border: 1px solid white !important;
        background-color: white !important;
    }
</style>
<div class="modal-header modal-header-windown">
    <div style="display: flex; justify-content: space-between;">
        <h4 class="modal-title" style="font-weight: 600; font-size: 25px; color: black; margin-left: 15px;">Adicionar imagem</h4>
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="display:flex; margin-top: 5px; gap: 15px;">
                <a name="#section1" onclick="verify_expand(this.name)">
                    Dados do artigo
                </a>
            </div>
            <button type="button" class="close" data-dismiss="modal" style="font-size: 30px !important; margin-top: 5px; margin-right: 15px; color:#ec4758; opacity: 100% !important;">&times;</button>
        </div>
    </div>
</div>
<div class="modal-body modal-body-windown">
    <input type="hidden" name="id_artigo_editar" id="id_artigo_editar" value="<?= $artg->getId(); ?>">
    <div class="ibox float-e-margins border-bottom" style="color: black;" id="section1">
        <div class="ibox-title">
            <h5 style="font-size: 15px; font-weight: 1000;">Dados do artigo</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up" name="expand_section" id="expand_section1"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content" name="content_section1" id="dados_gerais_container">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="img">Imagem: <span style="color: red;">*</span></label>
                        <input type="file" class="form-control" name="img" accept="img/*" id="img">
                        <label for="img" id="categoria_error" class="error" style="display: none;">Não pode estar vazia</label>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-lg-12">
                    <span style="color: red;"><strong>*</strong> Campos obrigatórios </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer modal-footer-windown">
    <div class="separte_buttons">
        <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" id="editar_artigo_action" onclick="salvar_artigo_img()">Salvar Imagem</button>
    </div>
</div>

<script>
    function salvar_artigo_img() {
        let exec = 0;
        document.getElementById("salvar_artigo_action").disabled = true;

        var imgInput = document.getElementById('img');
        var imgFile = imgInput.files[0]; // Obtém o arquivo selecionado
        var id_artigo = document.getElementById('id_artigo_editar').value; // Obtém o ID do produto

        if (!imgFile) {
            document.getElementById("img").style.border = "1px solid red";
            document.getElementById("img_error").style.display = "block";
            setTimeout(() => {
                document.getElementById("img").style.border = "1px solid #e5e6e7";
                document.getElementById("img_error").style.display = "none";
            }, 2300);
            document.getElementById("salvar_artigo_action").disabled = false;
            return;
        }

        // Enviar imagem para o servidor
        const formData = new FormData();
        formData.append("img", imgFile);
        formData.append("id", id_artigo); // Envia o ID do produto para o backend

        fetch('../../artigos/control/upload.php', { // Endpoint correto
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.imgUrl) {
                let imgUrl = data.imgUrl; // URL da imagem salva

                let dadosProduto = {
                    id: id_artigo, // Passa o ID do produto
                    img: imgUrl // Usa a URL da imagem salva
                };

                // Agora, envie a URL da imagem para o banco de dados
                fetch('../../artigos/control/artigo_atualizar_img.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosProduto),
                })
                .then(response => response.text())
                .then(verifica => {
                    if (verifica.trim() === "200") {
                        mostrar_mensagem("Imagem atualizada com sucesso!");
                        setTimeout(() => {
                            document.getElementById("salvar_artigo_action").disabled = false;
                        }, 5);
                    } else {
                        mostrar_mensagem("Houve um erro ao atualizar a imagem.");
                        document.getElementById("salvar_artigo_action").disabled = false;
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar a imagem:", error);
                    document.getElementById("salvar_artigo_action").disabled = false;
                });

            } else {
                console.error("Erro no upload da imagem:", data.error);
                document.getElementById("salvar_artigo_action").disabled = false;
            }
        })
        .catch(error => {
            console.error("Erro ao enviar a imagem:", error);
            document.getElementById("salvar_artigo_action").disabled = false;
        });
    }

   
    function expand_dados_gerais_artigo_editar() {
        let verifica_expand_dados_gerais = document.getElementById("expand_section1")
        if (verifica_expand_dados_gerais.className.includes('fa-chevron-down')) {
            verifica_expand_dados_gerais.classList.remove('fa-chevron-down')
            verifica_expand_dados_gerais.classList.remove('fa-chevron-up')
            verifica_expand_dados_gerais.classList.add('fa-chevron-up')
            document.getElementById("dados_gerais_container").style.display = 'block'
        }
    }
</script>