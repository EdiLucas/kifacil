<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Estudante\PerfilController;

/*
|--------------------------------------------------------------------------
| 1. ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Autenticação (Login / Registo / Logout)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Funcionalidades Públicas

// Radar de Vagas (Aponta para a view radar mantendo o nome estrutural)
Route::get('/radar-vagas', function () {
    return view('radar');
})->name('mapa.publico');

// Mural de Notícias (Consome a API através do teu NoticiaController)
Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
/*
|--------------------------------------------------------------------------
| 2. GRUPO PROTEGIDO (REQUER AUTENTICAÇÃO)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | A. ÁREA DO ADMINISTRADOR
    |----------------------------------------------------------------------
    */
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
        
        // Dashboard Principal & Logs
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/logs/exportar', [AdminController::class, 'exportarLogs'])->name('admin.logs.exportar');
        
        // CRUD Completo de Estudantes
        Route::get('/admin/estudantes', [AdminController::class, 'listarEstudantes'])->name('admin.estudantes');
        Route::post('/admin/estudantes/salvar', [AdminController::class, 'salvarEstudante'])->name('admin.estudantes.salvar');
        Route::post('/admin/estudantes/atualizar/{id}', [AdminController::class, 'atualizarEstudante'])->name('admin.estudantes.atualizar');
        Route::delete('/admin/estudantes/eliminar/{id}', [AdminController::class, 'eliminarEstudante'])->name('admin.estudantes.eliminar');
        
        // CRUD Completo de Empresas
        Route::get('/admin/empresas', [AdminController::class, 'listarEmpresas'])->name('admin.empresas');
        Route::post('/admin/empresas/salvar', [AdminController::class, 'salvarEmpresa'])->name('admin.empresas.salvar');
        Route::post('/admin/empresas/atualizar/{id}', [AdminController::class, 'atualizarEmpresa'])->name('admin.empresas.atualizar');
        Route::post('/admin/empresas/validar/{id}', [AdminController::class, 'validarEmpresa'])->name('admin.empresas.validar');
        Route::delete('/admin/empresas/eliminar/{id}', [AdminController::class, 'eliminarEmpresa'])->name('admin.empresas.eliminar');

        // CRUD Completo de Vagas de Estágio
        Route::get('/admin/vagas', [AdminController::class, 'listarVagas'])->name('admin.vagas');
        Route::post('/admin/vagas/salvar', [AdminController::class, 'salvarVaga'])->name('admin.vagas.salvar');
        Route::post('/admin/vagas/atualizar/{id}', [AdminController::class, 'atualizarVaga'])->name('admin.vagas.atualizar');
        Route::delete('/admin/vagas/eliminar/{id}', [AdminController::class, 'eliminarVaga'])->name('admin.vagas.eliminar');    
    });

    /*
    |----------------------------------------------------------------------
    | B. ÁREA DA EMPRESA
    |----------------------------------------------------------------------
    */
Route::middleware([\App\Http\Middleware\CheckRole::class . ':empresa'])->prefix('empresa')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Empresa\VagaController::class, 'index'])->name('empresa.dashboard');
    Route::post('/vagas/salvar', [\App\Http\Controllers\Empresa\VagaController::class, 'salvarVaga'])->name('empresa.vagas.salvar');
    Route::post('/perfil/atualizar', [\App\Http\Controllers\Empresa\VagaController::class, 'atualizarPerfil'])->name('empresa.perfil.atualizar');
});

    /*
    |----------------------------------------------------------------------
    | C. ÁREA DO ESTUDANTE
    |----------------------------------------------------------------------
    */
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':estudante'])->group(function () {
        // Painel Principal do Estudante
        Route::get('/dashboard', [PerfilController::class, 'dashboard'])->name('estudante.dashboard');
   Route::post('/perfil/atualizar', [PerfilController::class, 'atualizarPerfil'])->name('estudante.perfil.atualizar');
        });

});