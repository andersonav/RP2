import {
    contentLoading, 
    loadModal, 
    showMessageValidator, 
    promptConfirmSwal 
} from '../Utils';

import Swal from 'sweetalert2';

const init = () => {
    habilitaEventos();
    habilitaBotoes();

    $('#addFormUsuario').attr("enctype", "multipart/form-data");
    $('#editFormUsuario').attr("enctype", "multipart/form-data");
};

const modalObject = "#nivel1";
const grid = "#gridUsers";
const habilitaEventos = () => {
    $("#searchFilterUsers").on("submit", function(e){
        e.preventDefault();
        getFilterUsers();
    });
}

const habilitaBotoes = () => {
    $("#addUser").on("click", function(){
        const url = '/users/create';
        loadModal(url, function(){
            $("#addFormUsuario").on("submit", function(e){
                e.preventDefault();
                formUser();
            });
        });
    });

    $(".btnEditUser").on("click", function(){
        const id = $(this).attr("id");
        const url = `/users/edit/${id}`;

        loadModal(url, function(){
            eventsInModal();

            //SUBMIT FORM EDIT 
            $("#editFormUsuario").on("submit", function(e){
                e.preventDefault()
                formUser(id);
            }); 
        });
    });

    $(".btnDeleteUser").on("click", function(){
        const id = $(this).attr("id");
        const url = `/users/delete/${id}`;

        Swal.fire(promptConfirmSwal).then(result => {
            if(result.isConfirmed){
                deleteUser(url);
            }
        });
    });
}

const formUser = id  => {
    const form = typeof id === "undefined" ? "#addFormUsuario" : "#editFormUsuario";
    const url = typeof id === "undefined" ? "/users/store" : `/users/update/${id}`;

    var dataSerialized = new FormData();

    var file    = $("input[name='photo']")[0].files;
    dataSerialized.append('userPhoto', file[0]);

    var inputs  = $(form).serializeArray();

    inputs.map(function(input, index) {
        dataSerialized.append(input.name, input.value);
    });
    
    $.ajax({
        url,
        method: "POST",
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
            })

            $(modalObject).modal('hide');
            getFilterUsers();
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

const getFilterUsers = () => {
    const form = "#searchFilterUsers";
    const url = '/users/index';

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

const eventsInModal = () => {
    $("#radioChangePassword").on("change", function(){
        if(!!$('input[name=radioAlterarSenha]:checked').val()){
            $("#rowPass").show();
            $(`input[name="password"]`).prop("disabled", false);
            $(`input[name="password_confirmation"]`).prop("disabled", false);
        }else{
            $("#rowPass").hide();
            $(`input[name="password"]`).prop("disabled", true).val("");
            $(`input[name="password_confirmation"]`).prop("disabled", true).val("");
        }   
    });
}

const deleteUser = url => {
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
            getFilterUsers()
        }
    });
}

export default init