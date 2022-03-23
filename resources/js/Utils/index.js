import tinymce from "tinymce";

const init = () => {
    loadLibs();
    settingsTinyMCE();
}

export const settingsTinyMCE = () => {
    tinymce.init({
        content_style: "body { font-family: Arial, Helvetica, sans-serif}",
        selector: "#textAreaContent",
        height: "100%",
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor imagetools table"
        ],
        menubar: false,
        statusbar: false,
        toolbar: "insertfile undo redo | fontselect | styleselect | bold italic | alignleft aligncenter alignright alignjustify | table bullist numlist outdent indent | link image imagetools | print preview media template code | forecolor backcolor charmap emoticons fullscreen pagebreak visualchars visualblocks",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        font_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n',
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

export const loadLibs = () => {
    configSelect2();
    configMasks();
}

const configSelect2 = () => {
    $(".select2").select2();
}

const configMasks = () => {
    $(".phone").inputmask({
        mask: ["(99) 9999-9999", "(99) 99999-9999"],
        keepStatic: true,
    })

    $(".cnpjcpf").inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
    });

    $(".cpf").inputmask({
        mask: '999.999.999-99'
    });

    $(".cnpj").inputmask({
        mask: '99.999.999/9999-99'
    });
}

export const contentLoading = element => {
    element.html(`
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-spinner fa-spinner fa-spin"> </i> Carregando...
        </div>
    `);
}

export const loadModal = (url, callback = null) => {
    const elementModal = $("#nivel1");
    elementModal.modal('show', {
        backdrop: 'static'
    });

    elementModal.find('.modal-dialog').html(`
        <div class="alert alert-secondary" role="alert">
            <i class="fa fa-spinner fa-spin"> </i> Carregando...
        </div>
    `)

    elementModal.load(`${url} .modal-dialog`, function(){
        if(callback){
            callback();
        }

        loadLibs();
    });
}

export const showMessageValidator = (form, errors) => {
    $(".error_feedback").html("");
    $(form).find(".validation-error").removeClass("validation-error");

    const nameInputs = Object.keys(errors);

    nameInputs.forEach(value => {
        const elementInput = $(form).find(`[name='${value}']`);
        elementInput.addClass("validation-error");

        errors[value].forEach(value => {
            elementInput.parent().find(".error_feedback").append(
                `<span class="required-label"> ${value} </span>`
            )
        });
    });
}

export const showMessageValidatorToast = errors => {
    const namesInput = Object.keys(errors);
    if(!namesInput.length) return;

    namesInput.forEach(v => {
        errors[v].forEach(value => {
            toastr.error(value);
        })
    });
}

export const promptConfirmSwal = {
    title: 'Tem certeza?',
    text: 'Esta ação não pode ser revertida',
    icon:'warning',
    showCancelButton:true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, confirmar'
}

export const colors = {
    primary: "#5b73e8",
    success: "#34c38f"
}

export const updateSelectField = (element, url) => {
    $.ajax({
        type: "GET",
        url,
        dataType: "JSON",
        beforeSend:function(){
            element.html("<option> Carregando... </option>")
        },
        success: function (response) {
            element.html("");
            for (const cliente in response) {
                element.append(`<option value="${cliente}"> ${response[cliente]} </option>`);
            }
        },
        error:function(jqXHR, textstatus, error){
            toastr.error("Ocorreu um erro em atualizar a lista de clientes, a tela será atualizada");
            window.location.reload();
        },
    });
}

export const generateRandomString = () => {
    return  Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
}

export default init;



