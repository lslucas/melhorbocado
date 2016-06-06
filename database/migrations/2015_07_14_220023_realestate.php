<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Realestate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('realestate', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('titulo');
			$table->string('acao');
			$table->string('imovel');
			$table->string('corretor', 100);
			$table->string('tipo', 50);
			$table->string('conservacao', 50);
			$table->string('condominio');
			$table->string('deficiente');
			$table->string('mau');
			$table->string('nome_torre');
			$table->string('num_apartamento');
			$table->string('endereco');
			$table->string('complemento');
			$table->string('bairro');
			$table->string('cidade');
			$table->string('estado');
			$table->string('cep');
			$table->decimal('area_util_apto_m2', 5, 2);
			$table->decimal('area_total_m2', 5, 2);
			$table->decimal('tamanho_lote', 5, 2);
			$table->decimal('total_m2', 5, 2);
			$table->string('ano_construcao', 4);
			$table->float('valor_mensal_iptu');
			$table->float('valor_venda');
			$table->float('valor_condominio');
			$table->integer('qtd_torres');
			$table->integer('qtd_apto_por_andar');
			$table->integer('qtd_andares');
			$table->integer('qtd_apto_por_torre');
			$table->integer('qtd_apto_condominio');
			$table->string('comissao');
			$table->string('parceria');
			$table->float('valor_final');
			$table->text('condicoes');
			$table->text('referencia');
			$table->text('telefone');
			$table->text('celular');
			$table->text('telefone_comercial');
			$table->text('outro_telefone');
			$table->text('email');
			$table->text('email_alternativo');
			$table->tinyInteger('status')->default(0);
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
		Schema::drop('realestate');
	}


}
