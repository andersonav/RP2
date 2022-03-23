import { subtract } from 'lodash';
import Swal from 'sweetalert2';
import tinymce from 'tinymce';
import {
    loadModal,
    colors,
    updateSelectField,
    contentLoading,
    generateRandomString,
    promptConfirmSwal,
} from '../Utils';

import { settingsFormModal, formCliente } from './AppClientes';
import { formTiposLaudos } from './AppTiposLaudos';

var pavimentos = [];
var properties = [];
var widgets3 = [];
var imagesUploaded = [];
const init = () => {
    habilitaBotoes();
    habilitaEventos();


    Dropzone.autoDiscover = false;

    $("body").on("click", ".addLaudoImages", function () {
        var imagesPerColumn = $("#imagensPerColumn").val();
        var imagesLenght = parseInt($(".checkSelectLaudoImages:checked").length);
        var imagesPerGroup = imagesLenght;
        if ($("#imagensPerPage").val() && $('#typeDisplay').val() == 'column' && $('#typeDescription').val() == "group") {
            imagesPerGroup = $("#imagensPerPage").val() === 'all' ? imagesLenght : parseInt($("#imagensPerPage").val());
        }

        var groups = Math.ceil(imagesLenght / imagesPerGroup);

        for (var i = 0; i < groups; i++) {
            if ($("#typeDescription").val() == "group") {
                var htmlImages = `<br/><div style="width: 100%">
                                                <figure style="width: 100%; text-align: center">
                                        `;
            } else {
                var htmlImages = `<br/>
                                           <div style="width: 100%;">
                                           <table style="width: 20%" cellspacing="5" cellpadding="5" align="center">
                                                <thead>
                                                    <tr>
                                                `
                for (var indexColumn = 0; indexColumn < imagesPerColumn; indexColumn++) {
                    htmlImages += `<th></th>`;
                }
                htmlImages += `  </tr>
                                                </thead>
                                                <tbody>
                                        `;
            }

            var gridImage = 0;

            $(".checkSelectLaudoImages:checked").each(function (indexImage) {
                if ((indexImage + 1) > ((i + 1) * imagesPerGroup - imagesPerGroup)
                    && (indexImage + 1) <= ((i + 1) * imagesPerGroup)) {
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
                                            ${$("#typeDisplay").val() == 'row' || gridImage == imagesPerColumn - 1 ? '<br/>' : ''}
                                        `;
                        } else {
                            htmlImages += `
                                            ${gridImage == 0 ? `<tr>` : ''}
                                                <td>
                                                    <div style="display: block; text-align: center">
                                                        ${cloneElement.prop("outerHTML")}
                                                        <label class="imagesText"><small class="small-wrapper imageDescription">Descrição imagem</small></label>
                                                    </div>
                                                </td>
                                            ${$("#typeDisplay").val() == 'row' || gridImage == parseInt(imagesPerColumn) - 1 ? '</tr>' : ''}
                                        `;
                        }

                        gridImage++;
                    }
                }
            });

            if ($("#typeDescription").val() == "group") {
                htmlImages += `<figcaption class="imagesText"><small class="small-wrapper imageDescription">Descrição imagem</small></figcaption></figure></div>`;

            } else {
                htmlImages += `</tbody></table></div>`;
            }

            htmlImages += `<p></p>`


            tinymce.activeEditor.execCommand(
                'mceInsertContent',
                false,
                htmlImages
            );
        }

    });

    $("body").on("click", "#checkAllImages", function () {
        if ($(this).is(":checked")) {
            $("input[id^='check']").prop("checked", true);

        } else {
            $("input[id^='check']").prop("checked", false);
        }
    });

    $("body").on("change", "#typeDisplay", function () {
        if ($(this).val() == "column") {
            $("#divImagesPerColumn").attr("class", "col-4 d-flex flex-column");

        } else {
            $("#divImagesPerColumn").attr("class", "col-4 d-flex flex-column d-none");
        }
        if ($(this).val() == 'column' && $('#typeDescription').val() == "group") {
            $("#divImagesPerPage").attr("class", "col-4 d-flex flex-column");

        } else {
            $("#divImagesPerPage").attr("class", "col-4 d-flex flex-column d-none");
        }
    });

    $("body").on("change", "#typeDescription", function () {
        if ($('#typeDisplay').val() == 'column' && $(this).val() == "group") {
            $("#divImagesPerPage").attr("class", "col-4 d-flex flex-column");

        } else {
            $("#divImagesPerPage").attr("class", "col-4 d-flex flex-column d-none");
        }
    });

    $("body").on("change", ".checkSelectLaudoImages", function () {
        var isChecked = false;
        $(".checkSelectLaudoImages").each(function () {
            if ($(this).is(":checked")) {
                isChecked = true;
            }
        });

        if (isChecked) {
            $(".addLaudoImages").attr("class", "addLaudoImages btn btn-sm btn-primary");

        } else {
            $(".addLaudoImages").attr("class", "addLaudoImages btn btn-sm btn-primary d-none");
        }
    });

    $("body").on("change", "#checkAllImages", function () {

        if ($(this).is(":checked")) {
            $(".addLaudoImages").attr("class", "addLaudoImages btn btn-sm btn-primary");

        } else {
            $(".addLaudoImages").attr("class", "addLaudoImages btn btn-sm btn-primary d-none");
        }
    });
}


const habilitaEventos = () => {

    if ($("input[name='laudo_id']").length) {
        $.ajax({
            url: `/laudos/getWidget1/${$("input[name='laudo_id']").val()}`,
            method: "get",
            success: function (response) {
                response.map(function (pavement) {
                    let objectData = {
                        id: pavement.id,
                        nome_personalizado: pavement.name,
                        personalizado_tipo: pavement.type,
                        personalizado_numero_unidades: pavement.number_unit,
                        numero_pavimentos: pavement.pavement
                    }

                    pavimentos.push(objectData);

                    if (pavimentos.length) {
                        $("#gridPersonalizados").html("");

                        pavimentos.map(function (value, index) {
                            $("#gridPersonalizados").append(`
                                <p id="${value.id}" class="personalized" style="cursor:pointer;"> ${value.nome_personalizado} </p>
                            `);

                            renderPersonalizados();
                        });

                    }

                });
            }
        });

        $.ajax({
            url: `/laudos/getWidget2/${$("input[name='laudo_id']").val()}`,
            method: "get",
            success: function (response) {

                response.map(function (property) {
                    var arrProperties = [];
                    property.properties.map(function (nameProperty) {

                        var arrApartments = [];
                        property.apartments.map(function (nameApartment) {
                            if (nameApartment.property_id == property.id) {
                                console.log(nameApartment);
                                arrApartments.push({
                                    apartment_name: nameApartment.name_apartment,
                                    resident_name: nameApartment.name_resident,
                                });
                            }
                        });

                        arrProperties.push({
                            property_name: nameProperty.name,
                            apartments: arrApartments
                        });
                    });

                    let objectData = {
                        id: property.id,
                        widget2_name: property.name,
                        widget2_property_num: property.number_properties,
                        properties: arrProperties
                    }

                    properties.push(objectData);

                    if (properties.length) {

                        properties.map(function (value, index) {
                            $("#gridPersonalizadoImoveis").append(`
                                <p id="${value.id}" class="personalized2" style="cursor:pointer;"> ${value.widget2_name} </p>
                            `);

                            renderPersonalizadosImoveis();
                        });

                    }
                });

            }
        });
    }

    //EVENTS INDEX
    $("#searchFilterLaudos").on("submit", function (e) {
        e.preventDefault();
        getFilterLaudos();
    })

    //EVENTS CREATE
    $("#addNewCliente").on("click", function () {
        const url = '/clientes/create';

        loadModal(url, function () {
            settingsFormModal();

            $("#addFormCliente").on("submit", function (e) {
                e.preventDefault()
                formCliente(undefined, updateSelectField);
            });
        });
    });

    $("#backStep1").on("click", function () {
        $("#btnTabCliente").tab("show");
    });

    $("#step1").on("click", function () {
        $("#btnTabTipoLaudo").tab("show");
    });

    $("#optionsLaudoModelo").on("change", function () {
        if ($(this).val() == "") return;
        getCapitulosByLaudoModelo($(this).val());

        $('#optionsLaudoModeloEdit').attr('data-id', $(this).val()).removeClass('d-none');
    });

    $("#optionsLaudoModeloEdit").on("click", function () {
        const id = $(this).attr('data-id');
        if (id == "") return;

        const url = `/tiposLaudos/editTiposLaudos/modal/${id}`;

        loadModal(url, function () {
            // $("#nivel1").find('.modal-dialog').addClass('modal-dialog-scrollable');
            $("#editFormModalTipoLaudo").on("submit", function (e) {
                e.preventDefault();
                formTiposLaudos(id, true, function () {
                    $("#nivel1").modal('hide');
                    getCapitulosByLaudoModelo(id);
                });
            });
        });
    });

    //EDITOR
    $(".box-cols").on("click", function () {
        let createElement = this.outerHTML;

        if ($(this).attr("id") == "2-col") {
            createElement = `<table style="width:100%; height:30px;">
                                <tbody> <tr style="height:30px;"> <td> </td> <td> </td> </tr> </tbody>
                            </table>`
        }

        if ($(this).attr("id") == "3-col") {
            createElement = `<table style="width:100%; height:30px;">
                                <tbody> <tr style="height:30px;"> <td> </td> <td> </td> <td> </td> </tr> </tbody>
                            </table>`
        }

        tinymce.activeEditor.execCommand(
            'mceInsertContent',
            false,
            createElement
        );
    })

    $(".tipography").on("dblclick", function () {
        tinymce.activeEditor.execCommand(
            'mceInsertContent',
            false,
            this.outerHTML.replace('contenteditable="true"', '').replace('item-draggable', '')
        );
    })

    $(".addPacoteFiguras").on("click", function () {
        const url = '/laudos/createPacoteFiguras';
        loadModal(url, function () {
            loadDropzone();
        });
    });

    //WIDGET PERSONALIZADO
    var countCustomWidget1 = 1;
    $("#addNewPersonalizado").on("click", function () {
        const url = '/laudos/renderViewPersonalizado';

        loadModal(url, function () {
            settingsFormModalPersonalizado();

            $("#createPersonalizado").on("submit", function (e) {
                e.preventDefault();
                const valuesInput = Array.from($(".numPavimentos")).map(value => value.value);

                let objectData = {
                    id: generateRandomString(),
                    nome_personalizado: $(`input[name="nome_personalizado"]`).val(),
                    personalizado_tipo: $(`input[name="personalizado_tipo"]`).val(),
                    personalizado_numero_unidades: $(`input[name="personalizado_numero_unidades"]`).val(),
                    numero_pavimentos: valuesInput
                }

                $("#customWidget1").append(`<input type="hidden" name="customWidget1Name[]" value="${objectData.nome_personalizado}">`);
                $("#customWidget1").append(`<input type="hidden" name="customWidget1Type[]" value="${objectData.personalizado_tipo}">`);
                $("#customWidget1").append(`<input type="hidden" name="customWidget1NumberUnit[]" value="${objectData.personalizado_numero_unidades}">`);
                valuesInput.map(function (valueInput) {
                    $("#customWidget1").append(`<input type="hidden" name="customWidget1NumberPavement${countCustomWidget1}[]" value="${valueInput}">`);
                });

                pavimentos = [...pavimentos, !!Object.keys(objectData).length ? objectData : {}];
                $("#nivel1").modal('hide');
                renderPersonalizados();
            });
        });
    });

    //WIDGET PERSONALIZADO 2
    $("#addNewPersonalizadoImoveis").on("click", function () {
        const url = '/laudos/renderViewPersonalizadoImoveis';
        loadModal(url, function () {
            settingsFormModalPersonalizadoImoveis();

            $("#createPersonalizadoImoveis").on("submit", function (e) {
                e.preventDefault();

                // const valuesInput = Array.from($(".numPavimentos")).map(value => value.value);
                let valuesInput = [];

                $(`.widget2_property_name`).each(function (index) {
                    const property_key = $(this).attr('key');
                    let apartmentValues = [];

                    $(`.apartment-wrapper[key="${property_key}"] .apartment-single`).each(function (index) {
                        if ($(this).find('.widget2_apartment_name').length) {
                            apartmentValues.push({
                                apartment_name: $(this).find('.widget2_apartment_name').first().val(),
                                resident_name: $(this).find('.widget2_apartment_resident').first().val(),
                            });
                        }
                    });

                    valuesInput.push({
                        property_name: $(this).val(),
                        apartments: apartmentValues
                    });
                });

                let objectData = {
                    id: generateRandomString(),
                    widget2_name: $(`input[name="widget2_name"]`).val(),
                    widget2_property_num: $(`input[name="widget2_property_num"]`).val(),
                    properties: valuesInput
                }

                $("#customWidget2").append(`<input type="hidden" name="customWidget2Name[]" value="${objectData.widget2_name}">`);
                $("#customWidget2").append(`<input type="hidden" name="customWidget2PropertyNumber[]" value="${objectData.widget2_property_num}">`);
                objectData.properties.map(function (property, index) {
                    $("#customWidget2").append(`<input type="hidden" name="customWidget2NameProperty[]" value="${property.property_name}">`);
                    property.apartments.map(function (apartment, indexApartment) {
                        $("#customWidget2").append(`<input type="hidden" name="customWidget2NameApartment${index + 1}-${indexApartment + 1}[]" value="${apartment.apartment_name}">`);
                        $("#customWidget2").append(`<input type="hidden" name="customWidget2NameResident${index + 1}[]" value="${apartment.resident_name}">`);
                    });

                });

                properties.push(objectData);
                $("#nivel1").modal('hide');
                renderPersonalizadosImoveis();
            });
        });
    });

    $('#widget3_tipo').on('change', function () {
        if (!$(this).val()) {
            return;
        }
        widgets3 = [];

        $.ajax({
            type: "GET",
            url: "/laudos/getWidget3/?tipo=" + $(this).val(),
            dataType: "HTML",
            success: function (response) {
                $('#gridPersonalizado3').html('');
                let newResponse = JSON.parse(response);
                if (newResponse.length) {
                    newResponse.forEach(function (value, index) {
                        var dataHtml = value.data_html.replace('../storage/', '../../storage/');

                        let objectData = {
                            id: generateRandomString(),
                            widget2_name: value.nome_modelo,
                            data_html: dataHtml
                        }

                        $('#gridPersonalizado3').append(`
                            <p id="${objectData.id}" class="personalized3" style="cursor:pointer;"> ${value.nome_modelo} </p>
                        `);

                        widgets3.push(objectData);
                    });


                    renderPersonalizados3();
                } else {
                    $('#gridPersonalizado3').append(`
                        <span id="emptyWidget3"> Nenhum widget encontrado </span>
                    `);
                }
            },
            complete: function () {
                // $("#step2").prop("disabled", false);
            }
        });
    });

    //END WIDGET PERSONALIZADO
    $("#addLaudo").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Gerar lista de figuras?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: "Não"
        }).then(result => {
            formLaudo(result.isConfirmed)
        });
    });

    $("#editLaudo").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Gerar lista de figuras?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: "Não"
        }).then(result => {
            formEditLaudo(result.isConfirmed)
        });
    });

    $("#refreshKeys").on("click", function () {
        refreshKeys();
    })

    $("body").on("click", ".textPanel", function () {
        let tinyContent = $(tinymce.activeEditor.getBody());
        const id = $(this).attr('data-id');
        const element = tinyContent.find(`*[data-title="true"][data-id="${id}"]`).first();
        const elementRaw = element.get(0);

        var headerOffset = 10;
        var elementPosition = elementRaw.getBoundingClientRect().top;
        var offsetPosition = elementPosition - headerOffset;


        elementRaw.scrollIntoView(false);
        // tinymce.activeEditor.getParam().scrollTo({
        //     top: offsetPosition
        // });
        tinymce.activeEditor.selection.select(elementRaw);
    });
}

const habilitaBotoes = () => {
    // $(".pictures-laudo").on("dblclick", function(){
    //     tinymce.activeEditor.execCommand(
    //         'mceInsertContent',
    //         false,
    //         `<legend> ${this.outerHTML} <br/> <small> Descrição imagem </small> </legend> `
    //     )
    // });

    $(".btnDeleteLaudo").on("click", function () {
        const id = $(this).attr("id");
        const url = `/laudos/delete/${id}`

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação é irreversível!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: "Não"
        }).then(result => {
            if (result.isConfirmed) {
                deleteLaudo(url);
            }
        });
    });

    $(".removeImg").on("click", function () {
        const pathFileImg = $(this).attr("id");

        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não pode ser revertida',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, confirmar'
        })
            .then(result => {
                if (result.isConfirmed) {
                    deleteImageLaudo(pathFileImg.replaceAll('/', '-'));
                    removeImagesEditor(pathFileImg);
                }
            })
    });

    $(".editImg").click(function (e) {
        e.preventDefault();

        //* Abre o modal
        $('#pixieModalLong').modal('show');

        setTimeout(() => {
            var pathFileImg = $(this).attr("id");

            var pixie = new Pixie({
                baseUrl: window.location.origin + '/assets/images/pixie',

                ui: {
                    mode: 'inline',
                    nav: {
                        position: 'top',
                        replaceDefault: true,

                        items: [
                            // { name: 'filter', icon: 'filter-custom', action: 'filter' },
                            // { type: 'separator' },
                            { name: 'resize', icon: 'resize-custom', action: 'resize' },
                            { name: 'crop', icon: 'crop-custom', action: 'crop' },
                            { name: 'transform', icon: 'transform-custom', action: 'transform' },
                            { type: 'separator' },
                            { name: 'draw', icon: 'pencil-custom', action: 'draw' },
                            { name: 'text', icon: 'text-box-custom', action: 'text' },
                            { name: 'shapes', icon: 'polygon-custom', action: 'shapes' },
                            { name: 'stickers', icon: 'sticker-custom', action: 'stickers' },
                            // { name: 'frame', icon: 'frame-custom', action: 'frame' },
                            { type: 'separator' },
                            { name: 'corners', icon: 'rounded-corner-custom', action: 'corners' },
                            { name: 'background', icon: 'background-custom', action: 'background' },
                            { name: 'merge', icon: 'merge-custom', action: 'merge' },
                        ]
                    },

                    openImageDialog: {
                        show: false,
                    },

                    toolbar: {
                        rightItems: [
                            {
                                type: 'button', text: "", icon: 'close', action: function () {
                                    $('#pixieModalLong').modal('hide');
                                }
                            },
                        ]
                    }
                },

                languages: {
                    active: 'portuguese',
                    custom: {
                        portuguese: { "open": "Abrir", "close": "Fechar", "apply": "Aplicar", "transform": "Girar", "draw": "Desenhar", "text": "Texto", "shapes": "Formas", "stickers": "Adesivos", "frame": "Quadros", "corners": "Cantos", "background": "Fundo", "merge": "Masclar", "save": "Salvar", "filter": "filtro", "resize": "redimensionar", "crop": "cortar" },
                    }
                },
            });

            setTimeout(() => {
                pixie.resetAndOpenEditor({
                    image: pathFileImg,

                    onSave: function () {
                        var newImg = pixie.getTool('export').getDataUrl('png');

                        deleteImageLaudo(pathFileImg.replaceAll('/', '-'));
                        removeImagesEditor(pathFileImg);

                        imagesUploaded = imagesUploaded.filter((imgObj) => imgObj !== pathFileImg.replace('/storage', 'public'));

                        $.ajax({
                            type: "POST",
                            url: "/laudos/add-figure-pixie",
                            data: {
                                storage_code: $("#codStorage").val(),
                                image_upload: newImg
                            },
                            dataType: "json",
                            success: function (response) {
                                if (!response.error) {
                                    imagesUploaded.push(response.file);
                                    getPicturesLaudo($("#codStorage").val());
                                    $('#pixieModalLong').modal('hide');
                                }
                            }
                        });
                    }
                });
            }, 100);
        }, 50);
    });
}

const formLaudo = figure => {
    const url = !!figure ? `/laudos/generatePDF/${figure}` : '/laudos/generatePDF '
    const form = "#addLaudo";

    var dataSerialized = new FormData();

    var inputs = [...$(form).serializeArray()];

    inputs.map(function (input, index) {
        dataSerialized.append(input.name, input.value);
    });

    imagesUploaded.map(function (image) {
        dataSerialized.append('images[]', image);
    });

    $.ajax({
        type: "POST",
        url,
        data: dataSerialized,
        processData: false,
        contentType: false,
        beforeSend: function () {
            const buttonSubmit = $(form).find(".btnSubmit");
            buttonSubmit.each((index, element) => {
                $(element).prop("disabled", true).html(`
                    Carregando....
                `)
            })
        },
        success: function (response) {
            Swal.fire({
                toast: true,
                title: `<p style="color:#ffff"> ${response.msg} </p>`,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
                background: colors.primary,
            });

            if (!response.error) {
                window.location.href = "/laudos/index";
                window.open(response.file_anexo);
            }
        },
        error: function (jqXHR, textStatus, error) {
            toastr.error("Ocorreu um erro interno, tente novamente mais tarde");
        },
        complete: function () {
            const buttonSubmit = $(form).find(".btnSubmit");
            buttonSubmit.each((index, element) => {
                $(element).prop("disabled", false).html(`
                    Salvar
                `)
            })
        }
    });
}

const formEditLaudo = figure => {
    const url = !!figure ? `/laudos/editGeneratePDF/${figure}` : '/laudos/editGeneratePDF '
    const form = "#editLaudo";

    var dataSerialized = new FormData();

    var inputs = [...$(form).serializeArray()];

    inputs.map(function (input, index) {
        dataSerialized.append(input.name, input.value);
    });

    imagesUploaded.map(function (image) {
        dataSerialized.append('images[]', image);
    });

    $.ajax({
        type: "POST",
        url,
        data: dataSerialized,
        processData: false,
        contentType: false,
        beforeSend: function () {
            const buttonSubmit = $(form).find(".btnSubmit");
            buttonSubmit.each((index, element) => {
                $(element).prop("disabled", true).html(`
                    Carregando....
                `)
            })
        },
        success: function (response) {
            Swal.fire({
                toast: true,
                title: `<p style="color:#ffff"> ${response.msg} </p>`,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
                background: colors.primary,
            });

            if (!response.error) {
                window.location.reload(true);
                window.open(response.file_anexo);
            }
        },

        error: function (jqXHR, textStatus, error) {
            toastr.error("Ocorreu um erro interno, tente novamente mais tarde");
        },
        complete: function () {
            const buttonSubmit = $(form).find(".btnSubmit");
            buttonSubmit.each((index, element) => {
                $(element).prop("disabled", false).html(`
                    Salvar
                `)
            })
        }
    });
}

const settingsFormModalPersonalizado = () => {
    $("#newNumeroPavimentos").on("click", function () {
        let index = $("#rowInitPavimento").attr("key");
        index++

        let newRow = $("#rowInitPavimento").clone();
        newRow.removeAttr("id");
        newRow.find(`input[name="personalizado_numero_pavimentos[0][nome_pavimento]"]`)
            .attr("name", `personalizado_numero_pavimentos[${index}][nome_pavimento]`).val("");

        $("#rowInitPavimento").attr("key", index);
        $("#boxPavimentos").append(newRow);
    });
}

const settingsFormModalPersonalizadoImoveis = () => {
    $('#widget2_property_num').on('change', function () {
        $('#boxProperties').html('');
        if (parseInt($(this).val()) > 0) {

            for (let i = 0; i < parseInt($(this).val()); i++) {
                $('#boxProperties').append(`
                    <div class="row properties-single mt-2" key="${i + 1}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Imóvel ${i + 1} </label>
                                <input
                                key="${i + 1}"
                                type="text"
                                class="form-control widget2_property_name"
                                name="widget2_property_name[${i + 1}]"
                                placeholder="Nome Imóvel"
                                />
                            </div>
                        </div>
                        <div class="col-12 apartment-wrapper" key="${i + 1}">
                            <div class="row apartment-single mx-1 mt-2">

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mx-1 mt-2">
                                <div class="col-md-12">
                                    <a href="javascript:void(0)" class="btnNewApartment" key="${i + 1}"> <i class="fa fa-plus-square"> </i> Ap </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }

        }
    });

    $('body').on('click', '.btnNewApartment', function () {
        const key = $(this).attr('key');
        $(`.apartment-wrapper[key="${key}"]`).append(`
            <div class="row apartment-single mx-1 mt-2">
                <div class="col-4">
                    <label>Nome Apartamento</label>
                    <input type="text" class="form-control widget2_apartment_name" name="widget2_apartment_name[${key}][]">
                </div>
                <div class="col-4">
                    <label>Morador</label>
                    <input type="text" class="form-control widget2_apartment_resident" name="widget2_apartment_resident[${key}][]">
                </div>
            </div>
        `);
    });
}


const renderPersonalizados = () => {
    $("#gridPersonalizados").html("");

    if (!!pavimentos.length) {
        pavimentos.map((value, index) => (
            $("#gridPersonalizados").append(`
                <p id="${value.id}" class="personalized" style="cursor:pointer;"> ${value.nome_personalizado} </p>
            `)
        ));

        $(".personalized").on("click", function () {
            const thisPavimento = pavimentos.filter(value => value.id == $(this).attr("id"))[0];
            let stringPavimentos = "";
            const dataString = `<h3> ${thisPavimento.personalizado_tipo} - ${thisPavimento.personalizado_numero_unidades} Unidades </h3>
                                <p> <b> Número de pavimentos: </b> </p>
                            `
            !!thisPavimento.numero_pavimentos.length > 0 &&
                thisPavimento.numero_pavimentos.forEach(pavimento => {
                    stringPavimentos += `<p> <small> ${pavimento} </small> </p>`
                });

            tinymce.activeEditor.execCommand(
                'mceInsertContent',
                false,
                dataString + stringPavimentos
            )
        });
    }
}
const renderPersonalizadosImoveis = () => {
    $("#gridPersonalizadoImoveis").html("");

    if (!!properties.length) {
        properties.map((value, index) => (
            $("#gridPersonalizadoImoveis").append(`
                <p id="${value.id}" class="personalized2" style="cursor:pointer;"> ${value.widget2_name} </p>
            `)
        ));

        $(".personalized2").on("click", function () {
            const thisProperty = properties.filter(value => value.id == $(this).attr("id"))[0];
            let stringProperty = "";
            const dataString = `<h3> <b> ${thisProperty.widget2_name} </h3>`;
            !!thisProperty.properties.length > 0 &&
                thisProperty.properties.forEach((property, i) => {
                    let stringApartment = "";
                    stringProperty += `<p> <b> Imóvel ${i + 1} </b> - ${property.property_name} </p>`;

                    !!property.apartments.length > 0 &&
                        property.apartments.forEach((apartment, j) => {
                            stringApartment += `<p> &nbsp;&nbsp;&nbsp;&nbsp; ${apartment.apartment_name} - ${apartment.resident_name} </p>`;
                        });

                    stringProperty += stringApartment;
                });

            tinymce.activeEditor.execCommand(
                'mceInsertContent',
                false,
                dataString + stringProperty
            )
        });
    }
}

const renderPersonalizados3 = () => {
    $(".personalized3").on("click", function () {
        const thisWidget3 = widgets3.filter(value => value.id === $(this).attr("id"))[0];
        let dataString = thisWidget3.data_html;

        tinymce.activeEditor.execCommand(
            'mceInsertContent',
            false,
            dataString
        )
    });
}

const refreshKeys = () => {
    let tinyContent = $(tinymce.activeEditor.getBody());
    let titulos = tinyContent.find('.capituloText');
    let subtitles = tinyContent.find('.subcapituloText');
    let images = tinyContent.find('.imagesText .small-wrapper');

    $('#cardNavPanel .box-forms').first().html('');

    //PERCORRE OS CAPITULOS NUMERANDO-OS E NUMERANDO SEUS SUBCAPTIULOS
    titulos.each(function (index, element) {
        $(element).find(".indiceCap").remove();

        // console.log('rodou');

        index++

        if (!$(element).find(".indiceCap").length) {
            $(element).prepend(`<span class="indiceCap"> ${index} - </span>`);

            $(element).attr('data-id', index);
            $(element).attr('data-title', true);

            $('#cardNavPanel .box-forms').append(`
                <a href="javascript:void(0)" data-id="${index}" class="textPanel d-block">
                    ${element.innerText}
                </a>
            `);
        }

        let subTitulos = $(this).nextUntil($(titulos[index]), ".subcapituloText");
        if (!!subTitulos.length) {
            subTitulos.each(function (i, e) {
                i++;
                $(e).find(".indiceSubCap").remove();
                $(e).find(".hyphenSubCap").remove();
                if (!$(e).find(".indiceSubCap").length) {
                    $(e).prepend(`<span class="indiceSubCap">${index}.${i}</span>  <span class="hyphenSubCap">-</span> `);

                    $(e).attr('data-id', `${index}.${i}`);
                    $(e).attr('data-title', true);

                    $('#cardNavPanel .box-forms').append(`
                        <a href="javascript:void(0)" data-id="${index}.${i}" class="textPanel subcapituloTextPanel d-block">
                            &nbsp; ${e.innerText}
                        </a>
                    `);
                }
            });
        }
    });

    //PERCORRE OS SUBCAPITULOS E NUMERA OS N3
    subtitles.each(function (index) {
        let n3 = $(this).nextUntil(subtitles[index + 1], ".n3Text");
        if (!!n3.length) {
            n3.each(function (i, e) {
                $(e).find(".indiceN3").remove();
                if (!$(e).find(".indiceN3").length) {
                    let prevSubTitle = $(e).prevAll(".subcapituloText")[0];
                    let indiceSubCap = $(prevSubTitle).find(".indiceSubCap").text();

                    i++;
                    if (!$(e).find(".indiceN3").length) {
                        $(e).prepend(`<span class="indiceN3"> ${indiceSubCap}.${i} - </span>`);

                        $(e).attr('data-id', `${indiceSubCap}.${i}`);
                        $(e).attr('data-title', true);

                        if ($(`#cardNavPanel .box-forms .n3TextPanel[data-sub="${indiceSubCap}"]`).length) {
                            $(`#cardNavPanel .box-forms .n3TextPanel[data-sub="${indiceSubCap}"]`).last().after(`
                                <a href="javascript:void(0)" data-id="${indiceSubCap}.${i}" data-sub="${indiceSubCap}" class="textPanel n3TextPanel d-block">
                                    &nbsp;&nbsp; ${e.innerText}
                                </a>
                            `);
                        } else {
                            $(`#cardNavPanel .box-forms .subcapituloTextPanel[data-id="${indiceSubCap}"]`).after(`
                                <a href="javascript:void(0)" data-id="${indiceSubCap}.${i}" data-sub="${indiceSubCap}" class="textPanel n3TextPanel d-block">
                                    &nbsp;&nbsp; ${e.innerText}
                                </a>
                            `);
                        }

                    }
                }
            })
        }
    });

    //PECORRE AS IMAGENS E AS NUMERA
    images.each(function (index, element) {
        let imageDescription = $(element).html();
        $(element).find(".indiceImg").remove();

        // console.log('rodou');

        index++

        if (!$(element).find(".indiceImg").length) {
            $(element).html(`<div class="indiceImg"> Figura ${index} - <small class="imageDescription">${imageDescription}</small> </div>`);

            $(element).attr('data-id', index);
            $(element).attr('data-title', true);

            $('#cardNavPanel .box-forms').append(`
                <a href="javascript:void(0)" data-id="${index}" class="textPanel d-block">
                    ${element.innerText}
                </a>
            `);
        }

    });
}

const getCapitulosByLaudoModelo = idModelo => {
    $.ajax({
        type: "GET",
        url: "/laudos/getCapitulos/" + idModelo,
        dataType: "HTML",
        beforeSend: function () {
            contentLoading($("#boxSelectCapitulos"));
            $("#step2").prop("disabled", true);
        },
        success: function (response) {
            if (!!response) {
                $("#boxSelectCapitulos").html(response);
            }
            $(".inputcaps").on("change", function () {
                $(`input[subcap="${$(this).attr("key")}"]`).each((index, element) => {
                    element.checked = this.checked

                    $(`input[n3="${$(this).attr("key")}_${$(element).attr("key")}"]`).each((i, e) => {
                        e.checked = this.checked;
                    });
                });
            });
        },
        complete: function () {
            $("#step2").prop("disabled", false);
        }
    });
}

const getPicturesLaudo = codLaudo => {
    const url = `/laudos/getPicturesLaudo/${codLaudo}`;

    $.ajax({
        type: "GET",
        url,
        dataType: "HTML",
        beforeSend: function () {
            contentLoading($("#box-imagens"));
        },
        success: function (response) {
            $("#box-imagens").html(response);
            habilitaBotoes();
            $(".pictures-laudo").draggable({ helper: 'clone' });
        },
    });
}

const loadDropzone = () => {
    let dropzoneFiguras = new Dropzone("#upload-figuras-laudo", {
        url: '/laudos/uploadPacoteFiguras',
        method: 'POST',
        headers: {
            'X-CSRF-Token': $(`meta[name="csrf-token"]`).attr("content")
        },
        acceptedFiles: "image/*",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        autoDiscover: false,
    });

    $(document).on('paste', function (event) {
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        items.forEach(function (value, index) {
            if (value.kind === 'file') {
                // adds the file to your dropzone instance
                dropzoneFiguras.addFile(value.getAsFile())
            }
        });
    });

    $("#createLaudoFiguras").on("submit", function (e) {
        e.preventDefault();
        dropzoneFiguras.processQueue();
    });

    dropzoneFiguras.on("sendingmultiple", function (file, xhr, formData) {
        formData.append('cod_storage', $("#codStorage").val());
    });

    dropzoneFiguras.on("successmultiple", function (file, response) {
        response.files.map(function (attachment) {
            imagesUploaded.push(attachment);
        });

        if (!!response.cod_storage) {
            $("#codStorage").val(response.cod_storage);
            getPicturesLaudo(response.cod_storage);
        }

        toastr.success("Imagens adicionadas");
        $("#nivel1").modal('hide');
    })
}

const getFilterLaudos = () => {
    const grid = "#gridLaudos";
    const form = $("#searchFilterLaudos");

    $.ajax({
        type: "GET",
        url: "/laudos/index",
        data: form.serialize(),
        dataType: "HTML",
        beforeSend: function () {
            contentLoading($(grid))
        },
        success: function (response) {
            $(grid).html($(response).find(`${grid} >`));
            habilitaBotoes();
        }
    });
}



const deleteLaudo = url => {
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
                showConfirmButton: false,
                background: colors.primary,
                iconColor: "#FFFF",
            });
            getFilterLaudos();
        },
    });
}

const deleteImageLaudo = pathFile => {
    const url = `/laudos/${$("input[name='laudo_id']").val()}/removeFileImg/${pathFile}`;

    $.ajax({
        type: "DELETE",
        url: url,
        dataType: "JSON",
        success: function (response) {
            if (!response.error) {
                toastr.success(`Imagem excluída com sucesso!`);
                getPicturesLaudo($("#codStorage").val());
            } else {
                toastr.error(`Não foi possível excluír a imagem, tente novamente`);
            }
        },
    });
}

const removeImagesEditor = srcImg => {
    let bodyTinyMCE = $(tinymce.activeEditor.getBody());
    let editorImages = bodyTinyMCE.find(`img[src="${srcImg}"]`);

    if (!!editorImages.length) {
        editorImages.each(function (index) {
            let descriptImage = $(this).nextAll("small")[0];
            descriptImage.remove();
            $(this).remove();
        });
    }
}


// setTimeout(() => {
//     getPicturesLaudo($("#codStorage").val());
// }, 500);


export default init;
