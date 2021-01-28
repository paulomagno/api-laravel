<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

      /*
            Neste Projeto foi utilizado a biblioteca sanctum
            Instalação : rodar os comandos abaixo
            - composer require laravel/sanctum
            - php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
            - php artisan migrate

            Ir no arquivo Kernel em app/http

            Adicionar a linha abaixo na chave api

            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

      */
    public function up()
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
}
