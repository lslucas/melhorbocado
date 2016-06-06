<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \lslucas\Integracoes;

class CronXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:xml
                            {url : URL da fonte do xml}
                            {--E|estate_id= : ID da imobiliária no vivonorio}
                            {--I|integracao=? : Nome da integracao}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa xml de cliente ao banco do vivonorio de forma automatica';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url');
        $estate_id = $this->option('estate_id');
        $integracao = $this->option('integracao');

        $estate = \App\Estate::name($estate_id, true);

        if ( !$estate )
            return $this->error('Argumento de estate_id='.$estate_id.' não existe! Impossível continuar.');

        if ( !$integracao )
            return $this->error('Informe a option (--integracao) de qual integração deseja utilizar');

        $this->info('Importação de xml de '.$url.' para o cliente '.$estate);
        $this->info('Utilizando integração de '.$integracao);

        $Integracoes = new Integracoes\CronXml();
        $Integracoes->load(['integracao'=> $integracao, 'url' => $url, 'estate_id' => $estate_id]);
        $result = $Integracoes->proccess();

        $this->line($result);

    }
}
