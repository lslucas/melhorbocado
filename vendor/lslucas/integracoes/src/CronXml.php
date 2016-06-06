<?php namespace lslucas\Integracoes;

use \lslucas\Integracoes;
use Illuminate\Http\Request;

class CronXml {

    public $integracao = false, $request = null, $data = null, $importPhotos = true, $estate_id = null;

    public function load($request)
    {
		if ( $request['integracao']=='midas' )
        	$Integracoes = new Integracoes\Midas();
		elseif ( $request['integracao']=='brasilbrokers' )
        	$Integracoes = new Integracoes\BrasilBrokers();
		elseif ( $request['integracao']=='inforce' )
        	$Integracoes = new Integracoes\Inforce();
		elseif ( $request['integracao']=='ivalue' )
        	$Integracoes = new Integracoes\IValue();
		elseif ( $request['integracao']=='vivareal' )
        	$Integracoes = new Integracoes\VivaReal();
		elseif ( $request['integracao']=='phd' )
        	$Integracoes = new Integracoes\PHD();
		elseif ( $request['integracao']=='armacaobuzios' )
        	$Integracoes = new Integracoes\ArmacaoBuzios();
		elseif ( $request['integracao']=='plug7' )
        	$Integracoes = new Integracoes\Plug7();
		else
        	return 'Selecione uma integração!';

        $this->integracao = $Integracoes;
        $this->request = $request;

        return;
    }

    public function proccess()
    {
        if ( !$this->integracao )
            throw new \InvalidArgumentException('Integração não foi inicializada!');

        $request = $this->request;
        $Integracoes = $this->integracao;
        $importPhotos = isset($request['importPhotos']) ? $request['importPhotos'] : $this->importPhotos;

        $input = [
            'url' => $request['url'],
            'area' => 'integracao-imovel'
        ];

        $Integracoes->estate_id($request['estate_id']);
        $Integracoes->importPhotos($importPhotos);
        $Integracoes->load($input);
        $Integracoes->parse();
        $qtd = $Integracoes->save();

        echo 'Foram importados "'.$qtd.'" imóveis!'.PHP_EOL;

        return;
    }

}
