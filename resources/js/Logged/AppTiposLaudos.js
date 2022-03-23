import Swal from 'sweetalert2';
import tinymce from 'tinymce';
import {
    loadModal,
    colors,
    showMessageValidatorToast,
    contentLoading
} from '../Utils';
const init = () => {
    habilitaBotoes();
    habilitaEventos();
    settingsDraggable();
    setTinyMCE();
    settingsTinyMCESecondary("#textAreaContentHeader");
    settingsTinyMCESecondary("#textAreaContentFooter");

    $("body").on("change", ".checkSelectTipoLaudoImages", function () {
        var isChecked = false;
        $(".checkSelectTipoLaudoImages").each(function() {
            if ($(this).is(":checked")) {
                isChecked = true;
            }
        });

        if (isChecked) {
            $(".addTipoLaudoImages").attr("class", "addTipoLaudoImages btn btn-sm btn-primary");

        } else {
            $(".addTipoLaudoImages").attr("class", "addTipoLaudoImages btn btn-sm btn-primary d-none");
        }
    });
    $("body").on("click", "#checkAllTipoLaudoImages", function() {
        if ($(this).is(":checked")) {
            $("input[id^='check']").prop("checked", true);

        } else {
            $("input[id^='check']").prop("checked", false);
        }
    });
    $("body").on("change", "#checkAllTipoLaudoImages", function () {
        if ($(this).is(":checked")) {
            $(".addTipoLaudoImages")    .attr("class", "addTipoLaudoImages btn btn-sm btn-primary");

        } else {
            $(".addTipoLaudoImages").attr("class", "addTipoLaudoImages btn btn-sm btn-primary d-none");
        }
    });
    $("body").on("click", ".addTipoLaudoImages", function () {
        var imagesPerColumn = $("#imagensPerColumn").val();
        var imagesLenght = parseInt($(".checkSelectTipoLaudoImages:checked").length);
        var imagesPerGroup = imagesLenght;
        if($("#imagensPerPage").val() && $('#typeDisplay').val() == 'column' && $('#typeDescription').val() == "group") {
            imagesPerGroup = $("#imagensPerPage").val() === 'all' ? imagesLenght : parseInt($("#imagensPerPage").val());
        }

        var groups = Math.ceil(imagesLenght / imagesPerGroup);

        for (var i = 0; i < groups; i++) {
            if ($("#typeDescription").val() == "group") {
                var htmlImages          = `<br/><div style="width: 100%">
                                                <figure style="width: 100%; text-align: center">
                                        `;
            } else {
                var htmlImages          = `<br/>
                                           <div style="width: 100%;">
                                           <table style="width: 20%" cellspacing="5" cellpadding="5" align="center">
                                                <thead>
                                                    <tr>
                                                `
                                                        for (var indexColumn = 0; indexColumn < imagesPerColumn; indexColumn++) {
                                                            htmlImages += `<th></th>`;
                                                        }
                    htmlImages          +=       `  </tr>
                                                </thead>
                                                <tbody>
                                        `;
            }


            var gridImage = 0;



            $(".checkSelectTipoLaudoImages:checked").each(function(indexImage) {
                if((indexImage+1) > ((i+1) * imagesPerGroup - imagesPerGroup)
                    && (indexImage+1) <= ((i+1) * imagesPerGroup)) {
                    if ($(this).is(":checked")) {
                        if (gridImage == parseInt(imagesPerColumn)) {
                            gridImage = 0;
                        }

                        var cloneElement = $(`#image${$(this).attr("data-index")}`).clone();
                        if ($("#typeDescription").val() == "group") {
                            cloneElement.attr("width", `${$("#typeDisplay").val() == "row" ? '50%' : 50 / imagesPerColumn + "%"}`);

                        } else {
                            cloneElement.attr("width", `100%`);
                        }

                        if ($("#typeDescription").val() == "group") {
                            htmlImages += `
                                            ${cloneElement.prop("outerHTML")}
                                            ${$("#typeDisplay").val() == 'row' || gridImage == imagesPerColumn - 1  ? '<br/>' : ''}
                                        `;
                        } else {
                            htmlImages += `
                                            ${gridImage == 0 ? `<tr>` : ''}
                                                <td>
                                                    <div style="display: block; text-align: center">
                                                        ${cloneElement.prop("outerHTML")}
                                                        <label class="imagesText"><small class="small-wrapper"><span class="imageDescription">Descrição imagem</span></small></label>
                                                    </div>
                                                </td>
                                            ${$("#typeDisplay").val() == 'row' || gridImage == parseInt(imagesPerColumn) - 1  ? '</tr>' : ''}
                                        `;
                        }

                        gridImage++;
                    }
                }
            });

            if ($("#typeDescription").val() == "group") {
                htmlImages += `<figcaption class="imagesText"><small class="small-wrapper"><span class="imageDescription">Descrição imagem</span></small></figcaption></figure></div>`;

            } else {
                htmlImages += `</tbody></table></div>`;
            }


            tinymce.activeEditor.execCommand(
                'mceInsertContent',
                false,
                htmlImages
            );
        }
    });
}

const habilitaEventos = () => {
    $("#searchFilterTiposLaudos").on("submit", function(e){
        e.preventDefault();
        getFilterTiposLaudos();
    });

    $(".tiposLaudosPage #avanceStep").on("click", function(){
        $("#btnCriarCapaTab").tab("show");
    });

    $("#gerarCapa").on("click", function(){
        $("#btnCriarHeaderFooterTab").tab("show");
    });

    $("#gerarHeaderFooter").on("click", function(){
        $("#btnCaptiulosTab").tab("show");
    });

    $("body").on("click", ".tiposLaudosPage .duplicateRowSubcaptiulo", function(){
        settingsFormCapitulos($(this));
    });

    $("body").on("click", ".tiposLaudosPage .addSubCapN3", function(){
        settingsFormSubCapsN3($(this));
    });

    $("body").on("click", ".tiposLaudosPage .addCapitulo", function(){
        settingsBoxCapitulos($(this));
    });

    //SUBMIT FORM ADD LAUDO MODELO
    $("#addTipoLaudo").on("submit", function(e){
        e.preventDefault();
        formTiposLaudos(undefined, false, function(response) {
            if(!response.error){
                window.location.href = "/tiposLaudos/index";
            }
        });
    });

    //SUMBIT FORM EDIT LAUDO MODELO
    $("#editTipoLaudo").on("submit", function(e){
        e.preventDefault();
        const id = $("#idTipoLaudo").val();
        formTiposLaudos(id, false, function(response) {
            if(!response.error){
                window.location.href = "/tiposLaudos/index";
            }
        });
    });

    //EVENTOS DE EXCLUSÃO DE SUBCAPITULOS E CAPITULOS
    $(".tiposLaudosPage .deleteSubCap").on("click", function(){
        const element = $(this);
        const id = $(this).attr("id");
        const url = `/tiposLaudos/deleteSubCap/${id}`;

        deleteRegister(url, function(){
            element.closest('.row').remove();
        });
    });

    $(".tiposLaudosPage .deleteCapitulo").on("click", function(){
        const element = $(this);
        const id = $(this).attr("id");
        const url = `/tiposLaudos/deleteCap/${id}`;

        deleteRegister(url, function(){
            element.closest('#rowCaps').next('.row.form-row').remove();
            element.closest('#rowCaps').remove();
            updateBoxCapitulosKeys($('.rowCaps'));
        });
    });

    $(".tiposLaudosPage .deleteRemoveN3").on("click", function(){
        const id = $(this).attr("id");
        const url =  "/tiposLaudos/deleteSubCapN3/" + id;
        const element = $(this);
        deleteRegister(url, function(){
            element.closest(".boxN3Clone").remove();
        });
    });

    $('.tiposLaudosPage').on('shown.bs.tab', function (e) {
        if(e.target.id === 'btnCaptiulosTab') {
            $('#btnSaveTipoLaudoWrapper').fadeIn();
        } else {
            $('#btnSaveTipoLaudoWrapper').hide();
        }
    });

    $('#btnSaveTipoLaudo').on('click', function(e) {
        e.preventDefault();
        const id = $("#idTipoLaudo").val();
        formTiposLaudos(id, false, function(response) {
            if(!response.error){
                window.location.href = "/tiposLaudos/index";
            }
        });
    });
}

const habilitaBotoes = () => {
    $("#uploadImageLaudo").on("click", function(){
        var code = $("#codStorage").val();
        if(code == ""){
            var code = 0;
        }
        const url = `/tiposLaudos/renderUploadImage/${code}`;

        loadModal(url, function(){
            settingsDropzone();
        });
    });

    $(".tiposLaudosPage .btnRemoveImg").on("click", function(e){
        e.preventDefault();
        let file = $(this).attr("id").replaceAll('/', '-');
        const url = `/tiposLaudos/removeFile/${file}`;

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não pode ser revertida',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, confirmar'
        })
        .then(result => {
            if(result.isConfirmed){
                if (!$("#idTipoLaudo").length) {
                    $(this).parent().parent().remove();
                }

                deleteFile(url);
            }
        });

    });

    $(".btnDeleteTipoLaudo").on("click", function(){
        const id = $(this).attr("id");
        const url = `/tiposLaudos/deleteTipoLaudo/${id}`;

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
                    getFilterTiposLaudos();
                });
            }
        });
    });

    $(".tiposLaudosPage .imageEditor").on("dblclick", function(){
        tinymce.activeEditor.execCommand(
            'mceInsertContent',
            false,
            this.outerHTML
        );
    })
}

const settingsDraggable = () => {
    $(".tiposLaudosPage .item-draggable").draggable({
        helper: "clone",
        cursor:"move",
    });
}

const settingsTinyMCESecondary = (selector) => {
    tinymce.init({
        content_style: "body { font-family: Arial, Helvetica, sans-serif}",
        selector: selector,
        height: "100%",
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
        ],
        menubar: false,
        statusbar: false,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media template code | forecolor backcolor charmap emoticons fullscreen pagebreak visualchars visualblocks",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        language: "pt_BR",
        init_instance_callback:function(editor){
            editor.on('keydown', function(e){
                if(e.keyCode === 9){
                    if(e.shiftKey){
                        editor.execCommand('Outdent');
                    }else{
                        editor.execCommand('Indent');
                    }
                    e.preventDefault();
                    return false;
                }
            })
        }
    });
}

const setTinyMCE = () => {
    $(".tiposLaudosPage .box-content-laudo").droppable({
        drop:function(event, ui){
            let createElement = ui.draggable[0].outerHTML;
            if(ui.draggable.hasClass("pictures-laudo")){
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
    var code = $("#codStorage").val();
    var id = $("#idTipoLaudo").val();
    if(id == ""){
        var id = 0
    }
    if(code == "" || typeof code == "undefined"){
        var code = 0
    }

    let myDropzone = new Dropzone("#upload-img-tipos-laudos", {
        url: '/tiposLaudos/uploadImage/'+id+'/'+code,
        method: 'POST',
        headers:{
            'X-CSRF-Token': $(`meta[name="csrf-token"]`).attr("content")
        },
        acceptedFiles: "image/*",
        autoProcessQueue:false,
        uploadMultiple: true,
        parallelUploads:100,
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

    $("#uploadImageTiposLaudos").on("submit", function(e){
        e.preventDefault();
        myDropzone.processQueue();
    });
    myDropzone.on("success", function(file,response){
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
        if(!response.error){
            $("#nivel1").modal('hide');
            renderGridImagensAJAX(response.cod_storage);
        }
    })

}
const getPicturesTipoLaudo = codLaudo => {
    const url = `/tiposLaudos/getPicturesTipoLaudo/${codLaudo}`;

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
            $(".pictures-laudo").draggable({ helper: 'clone'});
        },
    });
}

const renderGridImagensAJAX = (code) => {
    const grid = "#box-imagens";
    const url = `/tiposLaudos/renderViewImage/${code}`;
    $("#codStorage").val(code);
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
            $(grid).html(response);
            habilitaBotoes();
            settingsDraggable();
        }
    });
}

export const formTiposLaudos = (id, editCapitulos = false, callback = null) => {
    tinymce.triggerSave();

    let url = '';
    if(editCapitulos) {
        url = `/tiposLaudos/updateTiposLaudos/modal/${id}`;
    } else {
        url = !id  ? '/tiposLaudos/saveTiposLaudos' : `/tiposLaudos/updateTiposLaudos/${id}`;
    }

    const form = !id  ? "#addTipoLaudo" : "#editTipoLaudo";
    const type = !id ? "POST" : "PUT";

    $.ajax({
        type,
        url,
        data: editCapitulos ? $('#editFormModalTipoLaudo').serializeArray() : $(form).serializeArray(),
        dataType: "JSON",
        beforeSend:function(){
            $("#btnSaveTipoLaudo").prop("disabled", true).html(`
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

            if(!!callback) {
                callback(response);
            }
        },
        error:function(jqXHR, textStauts, error){
            const errors = !!jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : {}
            showMessageValidatorToast(errors);
        },
        complete:function(){
            $("#btnSaveTipoLaudo").prop("disabled", false).html(`
                <i class="far fa-save"></i> Salvar
            `)
        }
    });
}

const getFilterTiposLaudos = () => {
    const grid = "#gridTiposLaudos";
    const form = $("#searchFilterTiposLaudos");

    $.ajax({
        type: "GET",
        url: "/tiposLaudos/index",
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

    console.log(newRow);
    console.log(element);
    console.log(index);

    newRow.attr("id", "cloneRow");
    newRow.attr("rowkey", index);
    newRow.attr("style", null);

    newRow.find(`input[name="capitulos[${indexRow}][subcapitulos][0][nome_subcapitulo]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][nome_subcapitulo]`).val("");

    newRow.find(`textarea[name="capitulos[${indexRow}][subcapitulos][0][texto_padrao]"]`)
        .attr("name", `capitulos[${indexRow}][subcapitulos][${index}][texto_padrao]`).val("");

    //SUBCAPS N3
    newRow.find(`button[href="#boxSubCapitulosN3_${indexRow}_0"]`).attr("href", `#boxSubCapitulosN3_${indexRow}_${index}`);
    newRow
        .find(`#boxSubCapitulosN3_${indexRow}_0`)
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
    $(".tiposLaudosPage .removeSubCap").on("click", function(){
        $(this).closest(".row").remove();
    });

    // $(".tiposLaudosPage .addSubCapN3").on("click", function(){
    //     if($(this).closest(".rowKey").attr("rowkey") == 0) return;
    //     settingsFormSubCapsN3($(this));
    // });
}

/**
 *
 * @param {JQuery<HTMLElement>} rowCaps
 */
const updateBoxCapitulosKeys = (rowCaps) => {
    rowCaps.each(function (i) {
        let element = $(this);

        element.next('.row.form-row').find('.addCapitulo').attr('key', i);

        element.attr('key', i);
        element.next('.row.form-row').find('.addCapitulo').attr('key', i);
        element.find('.capId').attr('name', `capitulos[${i}][id]`);
        element.find('.capName').attr('name', `capitulos[${i}][nome_capitulo]`);
        element.find('.capText').attr('name', `capitulos[${i}][texto_padrao]`);

        element.find('.rowKey').each(function (j) {
            let subCapElement = $(this);
            let indexSubCap = subCapElement.attr("rowkey");
            subCapElement.find('.subCapId').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][id]`);
            subCapElement.find('.subCapName').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][nome_subcapitulo]`);
            subCapElement.find('.subCapText').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][texto_padrao]`);

            subCapElement.find('.n3Button').attr('href', `#boxSubCapitulosN3_${i}_${indexSubCap}`);
            subCapElement.find('.n3Box').attr('id', `boxSubCapitulosN3_${i}_${indexSubCap}`);

            subCapElement.find('.rowN3').each(function (k) {
                let n3Element = $(this);
                let indexN3 = n3Element.attr("key");
                n3Element.find('.n3Id').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][n3][${indexN3}][id]`);
                n3Element.find('.n3Name').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][n3][${indexN3}][nome_sub_subcapitulo]`);
                n3Element.find('.n3Text').attr('name', `capitulos[${i}][subcapitulos][${indexSubCap}][n3][${indexN3}][texto_padrao]`);
            });
        });
    });
}

const settingsBoxCapitulos = (btn) => {
    const boxCaps = $("#boxCaps");
    const rowCapitulo = $("#rowCaps");
    let key = btn.attr('key');
    key++
    let newRow = rowCapitulo.clone();

    newRow.attr("key", key);
    // boxCaps.attr("lastkey", key);

    newRow.find(`input[name="capitulos[0][nome_capitulo]"`)
        .attr("name", `capitulos[${newRow.attr("key")}][nome_capitulo]`).val("");

    newRow.find(`input[name="capitulos[0][texto_padrao]"`)
        .attr("name", `capitulos[${newRow.attr("key")}][texto_padrao]`).val("");

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

    newRow.find(".subCapClone").first().val('0');
    newRow.find(".removeCapitulo").show();
    newRow.find(".boxN3Clone").remove();
    newRow.find(".capId").remove();
    newRow.find(".subCapId").remove();
    newRow.find(".n3Id").remove();
    //EXCLUI CAMPOS DE SUBCAPTIULOS ADICIONAIS
    newRow.find("#cloneRow").remove();

    //ADICIONA CLONE AO BOX
    if(key === 0) {
        newRow.prependTo("#boxCaps");
    } else {
        newRow.insertAfter(btn.parents('.row.form-row'));
    }

    let newRowBtn = rowCapitulo.next('.row.form-row').clone();
    newRowBtn.find('.addCapitulo').attr('key', key);
    newRowBtn.insertAfter(newRow);

    //ATUALIZA ATRIBUTO "KEY" (POSIÇÃO) DOS CAPITULOS e dos botoes
    $('.rowCaps').filter(function() { return $(this).attr('key') >= key } ).each(function(i) {
        if(i > 0) {
            let element = $(this);
            let elementKey = parseInt(element.attr('key'));

            element.attr('key', elementKey + 1);
            element.next('.row.form-row').find('.addCapitulo').attr('key', elementKey + 1);
            element.find('.capId').attr('name', `capitulos[${elementKey + 1}][id]`);
            element.find('.capName').attr('name', `capitulos[${elementKey + 1}][nome_capitulo]`);
            element.find('.capText').attr('name', `capitulos[${elementKey + 1}][texto_padrao]`);

            element.find('.rowKey').each(function (j) {
                let subCapElement = $(this);
                let indexSubCap = subCapElement.attr("rowkey");
                subCapElement.find('.subCapId').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][id]`);
                subCapElement.find('.subCapName').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][nome_subcapitulo]`);
                subCapElement.find('.subCapText').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][texto_padrao]`);

                subCapElement.find('.n3Button').attr('href', `#boxSubCapitulosN3_${elementKey + 1}_${indexSubCap}`);
                subCapElement.find('.n3Box').attr('id', `boxSubCapitulosN3_${elementKey + 1}_${indexSubCap}`);

                subCapElement.find('.rowN3').each(function (k) {
                    let n3Element = $(this);
                    let indexN3 = n3Element.attr("key");
                    n3Element.find('.n3Id').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][n3][${indexN3}][id]`);
                    n3Element.find('.n3Name').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][n3][${indexN3}][nome_sub_subcapitulo]`);
                    n3Element.find('.n3Text').attr('name', `capitulos[${elementKey + 1}][subcapitulos][${indexSubCap}][n3][${indexN3}][texto_padrao]`);
                });
            });
        }

    });

    if(!!newRow.find(".deleteCapitulo")){
        newRow.find(".deleteCapitulo")
            .removeClass("deleteCapitulo")
            .addClass("removeCapitulo")
            .show();
    }

    scrollToElement(newRow);
    // $(".duplicateRowSubcaptiulo").off('click');
    // $(".tiposLaudosPage .duplicateRowSubcaptiulo").on("click", function(){
    //     // if($(this).closest("#rowCaps").attr("key") == 0) return;
    //     console.log("Entrou Aqui");
    //     settingsFormCapitulos($(this));
    // });

    // $(".tiposLaudosPage .addSubCapN3").on("click", function(){
    //     // if($(this).closest("#rowCaps").attr("key") == 0) return;
    //     settingsFormSubCapsN3($(this));
    // });

    $(".tiposLaudosPage .removeSubCap").on("click", function(){
        $(this).closest(".row").remove();
    });

    $(".tiposLaudosPage .removeCapitulo").on("click", function(){
        $(this).closest('#rowCaps').next('.row.form-row').remove();
        $(this).closest("#rowCaps").remove();
        updateBoxCapitulosKeys($('.rowCaps'));
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
    $(".tiposLaudosPage .btnRemoveN3").on("click", function(e){
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
            var code = $("#cod_storage").val();
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

            if ($("#idTipoLaudo").length) {
                renderGridImagensAJAX(code);
            }
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
