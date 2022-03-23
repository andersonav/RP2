import Swal from 'sweetalert2';
import tinymce from 'tinymce';
import {
    loadModal,
    colors,
    showMessageValidatorToast,
    contentLoading
} from '../Utils';

const init = () => {
    Dropzone.autoDiscover = false;

    habilitaBotoes();
    habilitaEventos();
    settingsDraggable();
    setTinyMCE();
}

const habilitaEventos = () => {
    $("#searchFilterTiposEquipamentos").on("submit", function(e){
        e.preventDefault();
        getFilterTiposEquipamentos();
    });

    $(".tiposEquipamentosPage #avanceStep").on("click", function(){
        $("#btnCriarConteudoTab").tab("show");
    });

    $(".tiposEquipamentosPage .duplicateRowSubcaptiulo").on("click", function(){
        settingsFormCapitulos($(this));
    });

    //SUBMIT FORM ADD EQUIPAMENTO MODELO
    $("#addTipoEquipamento").on("submit", function(e){
        e.preventDefault();
        formTiposEquipamentos();
    });

    //SUMBIT FORM EDIT EQUIPAMENTO MODELO
    $("#editTipoEquipamento").on("submit", function(e){
        e.preventDefault();
        const id = $("#idTipoEquipamento").val();
        formTiposEquipamentos(id);
    });
}

const habilitaBotoes = () => {
    $("#uploadImageEquipamentos").on("click", function(){
        console.log("tipos equipamentos");
        const url = '/tiposEquipamentos/renderUploadImage';

        loadModal(url, function(){
            settingsDropzone();
        });
    });

    $(".tiposEquipamentosPage .btnRemoveImg").on("click", function(e){
        e.preventDefault();
        let file = $(this).attr("id").replaceAll('/', '-');

        const url = `/tiposEquipamentos/removeFile/${file}`;
        deleteFile(url);
    });

    $(".btnDeleteTipoEquipamento").on("click", function(){
        const id = $(this).attr("id");
        const url = `/tiposEquipamentos/deleteTipoEquipamento/${id}`;

        Swal.fire({
            title: "Tem certeza?",
            text: "Esta ação é irreversível!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, quero excluir!',
            cancelButtonText: 'Cancelar',
            reverseButtons:true,
        }).then(result => {
            if(result.isConfirmed){
                deleteRegister(url, function(){
                    getFilterTiposEquipamentos();
                });
            }
        });
    });

    $(".tiposEquipamentosPage .imageEditor").on("dblclick", function(){
        tinymce.activeEditor.execCommand(
            'mceInsertContent',
            false,
            this.outerHTML
        );
    })
}

const settingsDraggable = () => {
    $(".tiposEquipamentosPage .item-draggable").draggable({
        helper: "clone",
        cursor:"move",
    });
}

const setTinyMCE = () => {
    $(".tiposEquipamentosPage .box-content-laudo").droppable({
        drop:function(event, ui){
            let createElement = ui.draggable[0].outerHTML;
            if(ui.draggable.hasClass("pictures-equipamento")){
                createElement = `<legend> ${createElement} <br> <small> Descrição imagem </small> </legend> `
            }

            if(ui.draggable.attr("id") == "2-col"){
                createElement = `<table style="width:100%; height:30px;">
                                    <tbody> <tr style="height:30px;"> <td> </td> <td> </td> </tr> </tbody>
                                </table>`
            }

            if(ui.draggable.attr("id") == "3-col"){
                createElement =  `<table style="width:100%; height:30px;">
                                    <tbody> <tr style="height:30px;"> <td> </td> <td> </td> <td> </td> </tr> </tbody>
                                </table>`
            }

            tinymce.activeEditor.execCommand(
                'mceInsertContent',
                false,
                createElement
            );
        },
    })
}

const settingsDropzone = () => {
    let myDropzone = new Dropzone("#uploadImgTiposEquipamento", {
        url: '/tiposEquipamentos/uploadImage',
        method: 'POST',
        headers:{
            'X-CSRF-Token': $(`meta[name="csrf-token"]`).attr("content")
        },
        acceptedFiles: "image/*",
        autoProcessQueue:false,
        uploadMultiple: true,
        parallelUploads:100,
        autoDiscover:false,
    });

    $(document).on('paste', function(event){
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        items.forEach(function(value, index) {
            if (value.kind === 'file') {
                // adds the file to your dropzone instance
                myDropzone.addFile(value.getAsFile())
            }
        });
    });

    $("#uploadImageTiposEquipamentos").on("submit", function(e){
        e.preventDefault();
        myDropzone.processQueue();
    });

    myDropzone.on("success", function(file, response){
        Swal.fire({
            toast:true,
            title: `<p style="color:#ffff"> ${response.msg} </p>`,
            icon: !response.error ? 'success' : 'error',
            position: 'top-end',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton:false,
            background: colors.primary,
        });
        if(!!response.cod_storage){
            $("#codStorage").val(response.cod_storage);
            getPicturesEquipamento(response.cod_storage);
        }
        if(!response.error){
            $("#nivel1").modal('hide');
            renderGridImagensAJAX();
        }
    })
}
const getPicturesEquipamento = codLaudo => {
    const url = `/tiposEquipamentos/getPicturesEquipamento/${codLaudo}`;

    $.ajax({
        type: "GET",
        url,
        dataType: "HTML",
        beforeSend:function(){
            contentLoading($("#box-imagens"));
        },
        success: function (response) {
            $("#box-imagens").html(response);
            habilitaBotoes();
            $(".imageEditor").draggable({ helper: 'clone'});
        },
    });
}
const renderGridImagensAJAX = () => {
    const grid = "#gridImagens";
    const url = "/tiposEquipamentos/create";

    $.ajax({
        type: "GET",
        url,
        dataType: "HTML",
        beforeSend:function(){
            $(grid).html(`
                <p Carregando.... </p>`
            );
        },
        success: function (response) {
            $(grid).html($(response).find(`${grid} >`));
            habilitaBotoes();
            settingsDraggable();
        }
    });
}

const formTiposEquipamentos = id => {
    tinymce.triggerSave();

    const url = !id  ? '/tiposEquipamentos/saveTiposEquipamentos' : `/tiposEquipamentos/updateTiposEquipamentos/${id}`;
    const form = !id  ? "#addTipoEquipamento" : "#editTipoEquipamento";
    const type = !id ? "POST" : "PUT";

    $.ajax({
        type,
        url,
        data: $(form).serializeArray(),
        dataType: "JSON",
        beforeSend:function(){
            $("#btnSaveTipoEquipamento").prop("disabled", true).html(`
                <i class="fa fa-spinner fa-spin"> </i> Carregando...
            `)
        },
        success: function (response) {
            Swal.fire({
                toast:true,
                title: `<b style="color:#ffff;"> ${response.msg} </b>`,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton:false,
                background: colors.primary,
                iconColor: "#FFFF",
            });

            if(!response.error){
                window.location.href = "/tiposEquipamentos/index";
            }
        },
        error:function(jqXHR, textStauts, error){
            const errors = !!jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : {}
            showMessageValidatorToast(errors);
        },
        complete:function(){
            $("#btnSaveTipoEquipamento").prop("disabled", false).html(`
                <i class="far fa-save"></i> Salvar Tipo de Equipamento
            `)
        }
    });
}

const getFilterTiposEquipamentos = () => {
    const grid = "#gridTiposEquipamentos";
    const form = $("#searchFilterTiposEquipamentos");

    $.ajax({
        type: "GET",
        url: "/tiposEquipamentos/index",
        data: $(form).serialize(),
        dataType: "HTML",
        beforeSend:function(){
            contentLoading($(grid))
        },
        success: function (response) {
            $(grid).html($(response).find(`${grid} >`));
            habilitaBotoes();
        }
    });
}

const settingsFormCapitulos = element => {
    let index =  element.closest("#rowCaps").find("#boxSubCapitulos").attr("key");
    let indexRow = element.closest("#rowCaps").attr("key");
    let input = $(`input[name="capitulos[${indexRow}][subcapitulos][0][nome_subcapitulo]"]`);
    index++;

    const newRow = input.closest('.row').clone();

    newRow.attr("id", "cloneRow");
    newRow.attr("rowkey", index);

    newRow.find(`input[name="capitulos[${indexRow}][subcapitulos][0][nome_subcapitulo]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][nome_subcapitulo]`).val("");

    newRow.find(`textarea[name="capitulos[${indexRow}][subcapitulos][0][texto_padrao]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][texto_padrao]`).val("");

    //SUBCAPS N3
    newRow.find(`button[href="#boxSubCapitulosN3_0_0"]`).attr("href", `#boxSubCapitulosN3_${indexRow}_${index}`);
    newRow
        .find(`#boxSubCapitulosN3_0_0`)
        .attr("id", `boxSubCapitulosN3_${indexRow}_${index}`)

    newRow.find(`.containerN3`).attr("totalkey", 0);

    newRow
        .find(`input[name="capitulos[${indexRow}][subcapitulos][0][n3][0][nome_sub_subcapitulo]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][n3][0][nome_sub_subcapitulo]`)
        .val("");

    newRow
        .find(`textarea[name="capitulos[${indexRow}][subcapitulos][0][n3][0][texto_padrao]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][n3][0][texto_padrao]`)
        .val("");

    newRow.find(".removeSubCap").show();
    newRow.find(".boxN3Clone").remove();
    //END SUBCAPS N3

    newRow.appendTo(element.closest("#rowCaps").find("#boxSubCapitulos"));
    element.closest("#rowCaps").find("#boxSubCapitulos").attr("key", index);
    scrollToElement(newRow);
    $(".tiposEquipamentosPage .removeSubCap").on("click", function(){
        $(this).closest(".row").remove();
    });

    $(".tiposEquipamentosPage .addSubCapN3").on("click", function(){
        if($(this).closest(".rowKey").attr("rowkey") == 0) return;
        settingsFormSubCapsN3($(this));
    });
}

const settingsBoxCapitulos = () => {
    const boxCaps = $("#boxCaps");
    const rowCapitulo = $("#rowCaps");
    let lastIndex = boxCaps.attr("lastkey");
    lastIndex++
    let newRow = rowCapitulo.clone();

    newRow.attr("key", lastIndex);
    boxCaps.attr("lastkey", lastIndex);

    newRow.find(`input[name="capitulos[0][nome_capitulo]"`)
        .attr("name", `capitulos[${newRow.attr("key")}][nome_capitulo]`).val("");

    newRow.find(`input[name="capitulos[0][subcapitulos][0][nome_subcapitulo]"]`)
        .attr("name", `capitulos[${newRow.attr("key")}][subcapitulos][0][nome_subcapitulo]`).val("");

    newRow.find(`textarea[name="capitulos[0][subcapitulos][0][texto_padrao]"]`)
        .attr("name", `capitulos[${newRow.attr("key")}][subcapitulos][0][texto_padrao]`).val("");

    newRow.find(`button[href="#boxSubCapitulosN3_0_0"]`)
        .attr("href", `#boxSubCapitulosN3_${newRow.attr("key")}_0`);

    newRow.find(`#boxSubCapitulosN3_0_0`)
        .attr("id", `boxSubCapitulosN3_${newRow.attr("key")}_0`);

    newRow.find(`input[name="capitulos[0][subcapitulos][0][n3][0][nome_sub_subcapitulo]"]`)
        .attr("name", `capitulos[${newRow.attr("key")}][subcapitulos][0][n3][0][nome_sub_subcapitulo]`).val("");

    newRow.find(`textarea[name="capitulos[0][subcapitulos][0][n3][0][texto_padrao]"]`)
        .attr("name", `capitulos[${newRow.attr("key")}][subcapitulos][0][n3][0][texto_padrao]`).val("");

    newRow.find(".removeCapitulo").show();
    newRow.find(".boxN3Clone").remove();
    //EXCLUI CAMPOS DE SUBCAPTIULOS ADICIONAIS
    newRow.find("#cloneRow").remove();

    //ADICIONA CLONE AO BOX
    newRow.appendTo("#boxCaps");
    if(!!newRow.find(".deleteCapitulo")){
        newRow.find(".deleteCapitulo")
            .removeClass("deleteCapitulo")
            .addClass("removeCapitulo")
            .show();
    }

    scrollToElement(newRow);
    $(".tiposEquipamentosPage .duplicateRowSubcaptiulo").on("click", function(){
        if($(this).closest("#rowCaps").attr("key") == 0) return;
        settingsFormCapitulos($(this));
    });

    $(".tiposEquipamentosPage .addSubCapN3").on("click", function(){
        if($(this).closest("#rowCaps").attr("key") == 0) return;
        settingsFormSubCapsN3($(this));
    });

    $(".tiposEquipamentosPage .removeSubCap").on("click", function(){
        $(this).closest(".row").remove();
    });

    $(".tiposEquipamentosPage .removeCapitulo").on("click", function(){
        $(this).closest("#rowCaps").remove();
    });
}

const settingsFormSubCapsN3 = element => {
    let indexRow = element.closest("#rowCaps").attr("key");
    let indexSubCap = element.closest(".rowKey").attr("rowkey");
    let boxElement = element.closest('#subCapsN3').find(".boxN3");
    let indexN3 = boxElement.parent().attr("totalkey");
    indexN3++
    let newBoxElement = boxElement.clone();

    newBoxElement.find(".rowRemoveN3").show();
    boxElement.parent().attr("totalkey", indexN3);

    newBoxElement.removeClass("boxN3").addClass("boxN3Clone");
    newBoxElement.attr("key", indexN3);

    newBoxElement
        .find(`input[name="capitulos[${indexRow}][subcapitulos][${indexSubCap}][n3][0][nome_sub_subcapitulo]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${indexSubCap}][n3][${newBoxElement.attr("key")}][nome_sub_subcapitulo]`)
        .val("");

    newBoxElement
        .find(`textarea[name="capitulos[${indexRow}][subcapitulos][${indexSubCap}][n3][0][texto_padrao]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${indexSubCap}][n3][${newBoxElement.attr("key")}][texto_padrao]`)
        .val("");

    element.closest("#subCapsN3").find(".containerN3").append(newBoxElement);
    scrollToElement(newBoxElement);

    if(!!newBoxElement.find(".deleteRemoveN3")){
        newBoxElement.find(".deleteRemoveN3")
            .removeClass("deleteRemoveN3")
            .addClass("btnRemoveN3");
    }
    //EVENT REMOVE
    $(".tiposEquipamentosPage .btnRemoveN3").on("click", function(e){
        e.preventDefault();
        $(this).closest(".boxN3Clone").remove();
    });
}

const scrollToElement = element => {
    $(window).scrollTop(element.offset().top);
}

const deleteFile = url => {
    $.ajax({
        type: "DELETE",
        url,
        dataType: "JSON",
        success: function (response) {
            Swal.fire({
                toast: true,
                title: `<p style="color:#FFFF"> ${response.msg} </p>`,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton:false,
                background: colors.primary,
                iconColor: "#FFFF",
            });

            renderGridImagensAJAX();
        },
    });
}

const deleteRegister = (url, callback = null) => {
    $.ajax({
        type: "DELETE",
        url,
        dataType: "JSON",
        success: function (response) {
            Swal.fire({
                toast: true,
                title: `<p style="color:#ffff"> ${response.msg} </p>`,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton:false,
                background: colors.primary,
                iconColor: "#FFFF",
            });

            if(!response.error){
                if(!!callback){
                    callback();
                }
            }
        },
        error:function(){
            toastr.error("Algo ocorreu errado na exclusão, tente novamente")
        }
    });
}

export default init;
