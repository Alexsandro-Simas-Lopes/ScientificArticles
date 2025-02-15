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

    .modal-to-window {
        width: 100vw !important;
        height: 100vh !important;
        margin: 0px !important;
    }

    .modal-to-window-content {
        background-color: #f1f3f7 !important;
        min-height: 100vh !important;
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
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css"/>

<div class="modal-header modal-header-windown">
    <div style="display: flex; justify-content: space-between;">
        <h4 class="modal-title" style="font-weight: 1200; font-size: 25px; color: black; margin-left: 15px;">Adicionar imagem</h4>
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
            <h5 style="font-size: 15px; font-weight: 1000;">Dados da Imagem</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up" name="expand_section" id="expand_section1"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content" name="content_section1" id="dados_gerais_container">
            <!-- SHOW UPLOAD IMAGE -->
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="">
                                <div class="file-manager">
                                    <h5>Show:</h5>
                                    <!-- <div class="hr-line-dashed"></div> -->
                                    <input type="file" class="form-control" name="img" accept="image/*" id="img" onchange="previewImage()" multiple>
                                    <label for="img" id="categoria_error" class="error" style="display: none;">Não pode estar vazia</label>
                                    
                                    <div class="hr-line-dashed"></div>
                                    <h5>Folders</h5>
                                    <ul class="folder-list" style="padding: 0">
                                        <li><a href=""><i class="fa fa-folder"></i> Uploads</a></li>
                                    </ul>
                                    <h5 class="tag-title"><label for="img">Imagem: <span style="color: red;">*</span></label></h5>
                                    <ul class="tag-list" style="padding: 0;">
                                        <img id="preview" src="#" alt="Pré-visualização" style="max-width: 300px; display: none; border: 1px solid #ddd; border-radius: 5px;" />  
                                    </ul>
                                    <button id="editar_artigo_action" class="btn btn-primary btn-block" style="display: none; max-width: 300px;" onclick="salvar_artigo_img()">Upload</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
    <div class="animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="file-box">
                    <div class="file">
                        <a href="#">
                            <span class="corner"></span>

                            <div class="icon">
                                <i class="fa fa-file"></i>
                            </div>
                            <div class="file-name">
                                Document_2014.doc
                                <br/>
                                <small>Added: Jan 11, 2014</small>
                            </div>
                        </a>
                    </div>

                </div>
                <div class="file-box">
                    <div class="file">
                        <a href="#">
                            <span class="corner"></span>

                            <div class="image">
                                <img alt="image" class="img-responsive" src="img/p1.jpg">
                            </div>
                            <div class="file-name">
                                Italy street.jpg
                                <br/>
                                <small>Added: Jan 6, 2014</small>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="modal-footer modal-footer-windown">
    <div class="separte_buttons">
        <button class="btn btn-success" type="button" data-dismiss="modal"> Voltar ao Lobby </button>
    </div>
</div>

<script>
    function salvar_artigo_img() {
        document.getElementById("salvar_artigo_action").disabled = true;

        var imgInput = document.getElementById('img');
        var imgFile = imgInput.files[0]; // Obtém o arquivo selecionado
        var id_artigo = <?php echo $artg->getId(); ?>; // Obtém o ID do artigo do PHP

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

        // Criar um nome fixo para as imagens (ex: artgimg1, artgimg2, ...)
        let nomeImagem = `artgimg${new Date().getTime()}`; // Usa timestamp para garantir nomes únicos

        // Enviar imagem para o servidor
        const formData = new FormData();
        formData.append("img", imgFile);
        formData.append("id_artigo", id_artigo); // Envia o ID do artigo
        formData.append("nomeImagem", nomeImagem); // Nome fixo da imagem

        fetch('../../artigos/control/upload.php', { // Endpoint correto
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.imgUrl) {
                let imgUrl = data.imgUrl; // URL da imagem salva

                let dadosImagem = {
                    artigo_id: id_artigo, // Relaciona a imagem ao artigo
                    caminho: imgUrl, // URL HTTPS da imagem
                    descricao: nomeImagem // Nome fixo da imagem
                };

                // Agora, envia os dados da imagem para o banco de dados
                fetch('../../artigos/control/artigo_salvar_imagem.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosImagem),
                })
                .then(response => response.text())
                .then(verifica => {
                    if (verifica.trim() === "200") {
                        mostrar_mensagem("Imagem salva com sucesso!");
                        setTimeout(() => {
                            document.getElementById("salvar_artigo_action").disabled = false;
                        }, 5);
                    } else {
                        mostrar_mensagem("Houve um erro ao salvar a imagem.");
                        document.getElementById("salvar_artigo_action").disabled = false;
                    }
                })
                .catch(error => {
                    console.error("Erro ao salvar a imagem no banco de dados:", error);
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

    // VISUALIZAR IMAGEM ANTES DE SALVA-LÁ
    function previewImage() {
        var input = document.getElementById('img');
        var preview = document.getElementById('preview');
        var ImgIdAdd = document.getElementById('editar_artigo_action');

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                ImgIdAdd.src = e.target.result;
                ImgIdAdd.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "";
            preview.style.display = 'none';
            ImgIdAdd.style.display = 'none';
        }
    }
</script>