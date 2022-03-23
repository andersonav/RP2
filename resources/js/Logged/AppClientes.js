import {
    contentLoading,
    loadModal,
    showMessageValidator,
    promptConfirmSwal
} from '../Utils';
import Swal from 'sweetalert2';

const modalObject   = "#nivel1";
const grid          = "#gridClientes";

const init = () => {
    habilitaEventos()
    habilitaBotoes();
}

const habilitaEventos = () => {
    $("#searchFilterClientes").on("submit", function(e){
        e.preventDefault();
        getFilterClientes();
    });
}

const habilitaBotoes = () => {
    $("#addCliente").on("click", function(){
        const url = '/clientes/create';

        $("body").on("change", ".cep", function() {
            var zipcode = $(this).val().replace('-', '');

            $.ajax({
                url: `http://viacep.com.br/ws/${zipcode}/json/`,
                method: "GET",
                crossDomain: true,
                contentType: "application/json",
                success: function(response) {
                    console.log(response);
                }
            });
        });
        
        loadModal(url, function(){
            settingsFormModal();

            $("#inputCnpjCpf").mask('99.999.999/9999-99');
            $(".cep").mask("99999-999");

            $("#addFormCliente").on("submit", function(e){
                e.preventDefault()
                formCliente();
            });

        });
    });

    $(".btnEditCliente").on("click", function(){
        const id = $(this).attr("id");
        const url = `/clientes/edit/${id}`;
        
        $("body").on("click", ".btnDeleteAttachment", function() {
            $(this).parent().parent().remove();

            $.ajax({
                url: `/clientes/delete/anexo/${id}`,
                method: "POST",
                data: {
                    attachment: $(this).attr("data-attachment")
                }
            });
        });

        $("body").on("click", ".btnDeleteAttachmentAddress", function() {
            $(this).parent().parent().remove();

            $.ajax({
                url: `/clientes/delete/anexo/endereco/${$(this).attr("data-id-address")}/`,
                method: "POST",
                data: {
                    attachment: $(this).attr("data-attachment")
                }
            });
        });
        
        loadModal(url, function(response){
            settingsFormModal();

            if ($("select[name='tipo_pessoa']").val() == "J") {
                $("#inputCnpjCpf").mask('99.999.999/9999-99');

            } else {
                $("#inputCnpjCpf").mask('999.999.999-99');

            }
            
            $(".cep").mask("99999-999");

            $("#editFormCliente").on("submit", function(e){
                e.preventDefault()
                formCliente(id);
            });
        });
    });

    $(".btnDeleteCliente").on("click", function(){
        const id = $(this).attr("id");
        const url = `/clientes/delete/${id}`;

        Swal.fire(promptConfirmSwal).then(result => {
            if(result.isConfirmed){
                deleteCliente(url);
            }
        });
    });
}

export const formCliente = (id, callback = null) => {
    const form = typeof id === "undefined" ? "#addFormCliente" : "#editFormCliente";
    const url = typeof id === "undefined" ? "/clientes/store" : `/clientes/update/${id}`;

    var dataSerialized = new FormData();

    for (let index = 0; index < $("input[name='attachments']")[0].files.length; index++) {
        dataSerialized.append('attachments[]', $("input[name='attachments']")[0].files[index]);
    }

    $(".cep").each(function(indexCep) {
        for (let indexAddress = 0; indexAddress < $("input[name='attachmentsAddress"+indexCep+"']")[0].files.length; indexAddress++) {
            dataSerialized.append('attachmentsAddress'+indexCep+'[]', $("input[name='attachmentsAddress"+indexCep+"']")[0].files[indexAddress]);
        }
    });
    

    var inputs  = $(form).serializeArray();

    inputs.map(function(input, index) {
        dataSerialized.append(input.name, input.value);
    });

    $.ajax({
        method: "POST",
        url,
        processData: false,
        contentType: false,
        data: dataSerialized,
        dataType: "JSON",
        beforeSend:function(){
            $("#btnSubmit").prop("disabled", true).html(
                `<i class="fa fa-spinner fa-spin"> </i> Carregando....`
            )
        },
        success: function (response) {
            Swal.fire({
                toast:true,
                title: response.msg,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton:false,
                didOpen:(toast) => {
                    toast.addEventListener('mouseover', () => {
                        Swal.stopTimer();
                    })

                    toast.addEventListener('mouseleave', () => {
                        Swal.resumeTimer()
                    })
                }
            });

            $(modalObject).modal('hide');
            getFilterClientes();

            if(!!callback){
                callback($("#optionsCliente"), '/clientes/JSONClientes');
            }
        },
        error:function(jqXHR, textStatus, error){
            const errors = !!jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : [];
            showMessageValidator(form, errors);
        },
        complete:function(){
            $("#btnSubmit").prop("disabled", false).html(
                `Salvar`
            )
        }
    });
}

const getFilterClientes = () => {
    const form = "#searchFilterClientes";
    const url = '/clientes/index';

    $.ajax({
        type: "GET",
        url: url,
        data: $(form).serialize(),
        dataType: "HTML",
        beforeSend:function(){
            contentLoading($(grid));
        },
        success: function (response) {
            $(grid).html($(response).find(`${grid} >`));
            habilitaBotoes()
        },
    });
}

export const settingsFormModal = () => {
    $(`select[name="tipo_pessoa"]`).on("change", function(){
        switch($(this).val()){
            case "J":
                $(`label[for='cnpjcpf']`).html('CNPJ ')
                $("#inputCnpjCpf").mask('99.999.999/9999-99');
            break;
            case "F":
                $(`label[for='cnpjcpf']`).html('CPF ');
                $("#inputCnpjCpf").mask('999.999.999-99');
            break;
            case "":
                $("label[for='cgc']").text(`CPF/CPNJ`);
                $("input[name='cnpjcpf']").removeClass('cnpj cpf').addClass("cnpjcpf");
            break;
        }

        $("input[name='cnpjcpf']").val("");
    });

    var countAddress    = $(".cep").length;

    $('#addAddress').on('click', function() {
        
        $('#customer-address-wrapper').append(`
            <div class="card border border-primary customer-address-single">
                <div class="card-body">
                    <div class="row form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> CEP </label>
                                <input id="cep${countAddress}" name="cep[]" class="form-control cep" type="text" data-index="${countAddress}" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label> Endereço </label>
                                <input id="endereco${countAddress}" name="endereco[]" class="form-control" type="text" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label> Bairro </label>
                                <input id="bairro${countAddress}" name="bairro[]" class="form-control" type="text" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Cidade  </label>
                                <input id="cidade${countAddress}" name="cidade[]" class="form-control" type="text" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label> Número </label>
                                <input id="number${countAddress}" name="numero[]" class="form-control" type="number">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Estado  </label>
                                <input id="estado${countAddress}" name="estado[]" class="form-control" type="text" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> País  </label>
                                <input id="pais${countAddress}" name="pais[]" class="form-control" type="text" />

                                <div class="error_feedback"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-row">
                    <div class="col-12">
                        <label> Anexos </label>
                        <input type="file" name="attachmentsAddress${countAddress}" id="attachmentsAddress${countAddress}" class="form-control" multiple>
    
                        <div class="error_feedback"> </div>
                    </div>
                </div>
                </div>
            </div>
        `);

        $(".cep").mask("99999-999");
        countAddress++;
    });


    $('.customer-address-remove').on('click', function() {
        $(this).parents('.customer-address-single').remove();

        $.ajax({
            url: `/clientes/delete/endereco/${$(this).attr("data-id")}`,
            method: "GET",
        });
    });
}

const deleteCliente = url => {
    $.ajax({
        type: "DELETE",
        url: url,
        dataType: "JSON",
        success: function (response) {
            Swal.fire({
                toast:true,
                title: response.msg,
                icon: !response.error ? 'success' : 'error',
                position: 'top-end',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton:false,
                didOpen:(toast) => {
                    toast.addEventListener('mouseover', () => {
                        Swal.stopTimer();
                    })

                    toast.addEventListener('mouseleave', () => {
                        Swal.resumeTimer()
                    })
                }
            })
            getFilterClientes()
        }
    });
}

export default init;
