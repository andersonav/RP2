<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//NAMESPACES
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\LaudoModeloController;
use App\Http\Controllers\LaudoController;
use App\Http\Controllers\EquipamentoModeloController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();
Route::GET('/', [App\Http\Controllers\HomeController::class, 'root']);

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'users'], function(){
        Route::GET('/index', [UsersController::class, 'index'])->name('users.index');
        Route::GET('/create', [UsersController::class, 'create'])->name('users.create');
        Route::POST('/store', [UsersController::class, 'store'])->name('users.store');
        Route::GET('/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
        Route::POST('/update/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::DELETE('/delete/{id}',[UsersController::class, 'destroy'])->name('users.destroy');
    });

    Route::group(['prefix' => 'clientes'], function(){
        Route::GET('/index', [ClientesController::class, 'index'])->name('clientes.index');
        Route::GET('/create', [ClientesController::class, 'create'])->name('clientes.create');
        Route::POST('/store', [ClientesController::class, 'store'])->name('clientes.store');
        Route::GET('/edit/{id}', [ClientesController::class, 'edit'])->name('clientes.edit');
        Route::POST('/update/{id}', [ClientesController::class, 'update'])->name('clietnes.update');
        Route::DELETE('/delete/{id}', [ClientesController::class, 'destroy'])->name('clientes.delete');
        Route::GET('/JSONClientes', [ClientesController::class, 'getClientesJSON'])->name('clientes.getJSON');

        Route::POST('/delete/anexo/{id}', [ClientesController::class, 'deleteAttachment'])->name('clientes.deleteAttachment');
        Route::POST('/delete/anexo/endereco/{id}', [ClientesController::class, 'deleteAttachmentAddress'])->name('clientes.deleteAttachmentAddress');
        Route::GET('/delete/endereco/{id}', [ClientesController::class, 'deleteAddress'])->name('clientes.deleteAddress');
    });

    Route::group(['prefix' => 'tiposLaudos'], function(){
        Route::GET('/index', [LaudoModeloController::class, 'index'])->name('tiposLaudos.index');
        Route::GET('/create', [LaudoModeloController::class, 'create'])->name('tiposLaudos.create');

        Route::GET('/renderUploadImage/{code}', [LaudoModeloController::class, 'renderViewUploadImage'])
        ->name('tiposLaudos.renderViewUploadImage');

        Route::GET('/renderViewImage/{code}', [LaudoModeloController::class, 'renderViewImage'])
        ->name('tiposLaudos.renderViewImage');

        Route::POST('/uploadImage/{id}/{code}', [LaudoModeloController::class, 'uploadImage'])
            ->name('tiposLaudos.uploadImage');

        Route::DELETE('removeFile/{file}', [LaudoModeloController::class, 'removeFileFolder'])
            ->name('tiposLaudos.removeFileFolder');

        Route::POST('saveTiposLaudos', [LaudoModeloController::class, 'store'])
            ->name('tiposLaudos.saveTiposLaudos');

        Route::GET('editTiposLaudos/{id}', [LaudoModeloController::class, 'edit'])
            ->name('tiposLaudos.editTiposLaudos');

        Route::PUT('updateTiposLaudos/{id}', [LaudoModeloController::class, 'update'])
            ->name('tiposLaudos.updateTiposLaudos');

        Route::GET('editTiposLaudos/modal/{id}', [LaudoModeloController::class, 'editModal'])
            ->name('tiposLaudos.editModalTiposLaudos');

        Route::PUT('updateTiposLaudos/modal/{id}', [LaudoModeloController::class, 'updateModal'])
            ->name('tiposLaudos.updateModalTiposLaudos');

        Route::DELETE('deleteCap/{id}', [LaudoModeloController::class, 'destroyCapitulo'])
        ->name('tiposLaudos.deleteCap');

        Route::DELETE('deleteSubCap/{id}', [LaudoModeloController::class, 'destroySubCapitulo'])
            ->name('tiposLaudos.deleteSubCap');

        Route::DELETE('deleteSubCapN3/{id}', [LaudoModeloController::class, 'destroySubCapituloN3'])
        ->name('tiposLaudos.deleteSubCapN3');


        Route::DELETE('deleteTipoLaudo/{id}', [LaudoModeloController::class, 'destroy'])
        ->name('tiposLaudos.deleteTipoLaudo');
    });

    Route::group(['prefix' => 'tiposEquipamentos'], function(){
        Route::GET('/index', [EquipamentoModeloController::class, 'index'])->name('tiposEquipamentos.index');
        Route::GET('/create', [EquipamentoModeloController::class, 'create'])->name('tiposEquipamentos.create');

        Route::GET('/renderUploadImage', [EquipamentoModeloController::class, 'renderViewUploadImage'])
        ->name('tiposEquipamentos.renderViewUploadImage');

        Route::GET('getPicturesEquipamento/{codLaudo}', [EquipamentoModeloController::class, 'getPicturesEquipamento'])
            ->name('tiposEquipamentos.getPicturesEquipamento');

        Route::POST('/uploadImage', [EquipamentoModeloController::class, 'uploadImage'])
            ->name('tiposEquipamentos.uploadImage');

        Route::DELETE('removeFile/{file}', [EquipamentoModeloController::class, 'removeFileFolder'])
            ->name('tiposEquipamentos.removeFileFolder');

        Route::POST('saveTiposEquipamentos', [EquipamentoModeloController::class, 'store'])
            ->name('tiposEquipamentos.saveTiposEquipamentos');

        Route::GET('editTiposEquipamentos/{id}', [EquipamentoModeloController::class, 'edit'])
            ->name('tiposEquipamentos.editTiposEquipamentos');

        Route::PUT('updateTiposEquipamentos/{id}', [EquipamentoModeloController::class, 'update'])
            ->name('tiposEquipamentos.updateTiposEquipamentos');

        Route::DELETE('deleteCap/{id}', [EquipamentoModeloController::class, 'destroyCapitulo'])
        ->name('tiposEquipamentos.deleteCap');

        Route::DELETE('deleteSubCap/{id}', [EquipamentoModeloController::class, 'destroySubCapitulo'])
            ->name('tiposEquipamentos.deleteSubCap');

        Route::DELETE('deleteSubCapN3/{id}', [EquipamentoModeloController::class, 'destroySubCapituloN3'])
        ->name('tiposEquipamentos.deleteSubCapN3');


        Route::DELETE('deleteTipoEquipamento/{id}', [EquipamentoModeloController::class, 'destroy'])
        ->name('tiposEquipamentos.deleteTipoEquipamento');
    });

    Route::group(['prefix' => 'laudos'], function(){
        Route::GET('index', [LaudoController::class, 'index'])
            ->name('laudos.index');

        Route::GET('create', [LaudoController::class, 'create'])
            ->name('laudos.create');

        Route::GET('createEditor', [LaudoController::class, 'editor'])
            ->name('laudos.editor');

        Route::GET('getCapitulos/{id}', [LaudoController::class, 'renderCapitulosByLaudoModelo'])
            ->name('laudos.renderCapitulosByLaudoModelo');

        Route::GET('createPacoteFiguras', [LaudoController::class, 'renderModalCreateFiguras'])
            ->name('laudos.createPacoteFiguras');

        Route::POST('uploadPacoteFiguras', [LaudoController::class, 'uploadPicturesLaudos'])
            ->name('laudos.uploadFigurasLaudo');

        Route::GET('getPicturesLaudo/{codLaudo}', [LaudoController::class, 'getPicturesLaudos'])
            ->name('laudos.getPicturesLaudo');

        Route::GET('renderViewPersonalizado', [LaudoController::class, 'renderModalPersonalizado'])
            ->name('laudos.renderViewPersonalizado');

        Route::GET('renderViewPersonalizadoImoveis',[LaudoController::class, 'renderModalPersonalizadoImoveis'])
            ->name('laudos.renderModalPersonalizadoImoveis');

        Route::POST('generatePDF/{figure?}', [LaudoController::class, 'generatePDF'])
            ->name('laudos.generatePDF');

        Route::POST('editGeneratePDF/{figure?}', [LaudoController::class, 'editGeneratePDF'])
            ->name('laudos.editGeneratePDF');

        Route::GET('downloadPDF/{id}', [LaudoController::class, 'downloadPDF'])
            ->name('laudos.downloadPDF');

        Route::GET('edit/{id}', [LaudoController::class, 'edit'])
            ->name('laudos.edit');

        Route::DELETE('delete/{id}', [LaudoController::class, 'destroy'])
            ->name('laudos.delete');

        Route::POST('add-figure-pixie', [LaudoController::class, 'addFigurePixie'])
        ->name('laudos.addFigurePixie');

        Route::DELETE('{laudoId}/removeFileImg/{url}', [LaudoController::class, 'removeFileImg'])
            ->name('laudos.removeFileImg');

        Route::GET('getWidget1/{idLaudo}', [LaudoController::class, 'getWidget1'])
            ->name('laudos.getWidget1');

        Route::GET('getWidget2/{idLaudo}', [LaudoController::class, 'getWidget2'])
            ->name('laudos.getWidget2');

        Route::GET('getWidget3', [LaudoController::class, 'getWidget3'])
            ->name('laudos.getWidget3');

        Route::GET('duplicate/{id}', [LaudoController::class, 'duplicate'])
            ->name('laudos.duplicate');
    });
});


