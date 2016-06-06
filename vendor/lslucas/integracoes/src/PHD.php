<?php namespace lslucas\Integracoes;

use \lslucas\Files;
use \lslucas\Helpers;
use Nathanmac\Utilities\Parser\Parser;
use App\Http\Controllers\Admin\RealEstateController;
use App\RealEstate;

use Illuminate\Http\Request;

class PHD {

    public $storage_id = null, $data = null, $integracao = 'PHD', $importPhotos = true, $estate_id = null;

    public function load($input)
    {
        $Upload = new Files\Upload();

        $file = isset($input['url']) ? $input['url'] : \Request::file('file');

        $this->storage_id = $Upload->start($file, $input);
    }

    public function parse($storage_id=null)
    {
        if ( !is_null($storage_id) )
            $this->storage_id = $storage_id;

        if ( !$this->storage_id )
            throw new \InvalidArgumentException('Nenhum arquivo para se fazer parse');

        $Retrieve = new \lslucas\Files\Retrieve();
        $Parser = new Parser();

        $xml = $Retrieve->getContents($this->storage_id);
        $parsed = @$Parser->xml($xml)['Imoveis'];

        if ( !$parsed )
            throw new \InvalidArgumentException('XML inválido!');

        $this->data = $this->format($parsed);

        return $this->data;
    }

    public function format($payload)
    {
        $Numbers = new Helpers\Numbers();

        $formated = [];

        foreach ( $payload as $imoveis ) {

            $i=0;

            foreach ( $imoveis as $item ) {

                if ( isset($item['PrecoVenda']) && !empty($item['PrecoVenda']) ) {
                    $acao = 'venda';
                    $valor = $item['PrecoVenda'];

                } elseif ( isset($item['PrecoLocacao']) && !empty($item['PrecoLocacao']) ) {
                    $acao = 'locacao';
                    $valor = $item['PrecoLocacao'];

                } elseif ( isset($item['PrecoLocacaoTemporada']) && !empty($item['PrecoLocacaoTemporada']) ) {
                    $acao = 'temporada';
                    $valor = $item['PrecoLocacaoTemporada'];

                }

                // limpa . do valor para fazer uma conversao correta
                $valor = str_replace('.', '', $valor);
                $valorCondominio = isset($item['PrecoCondominio']) ? str_replace('.', '', $item['PrecoCondominio']) : 0;

                $titulo = $item['TipoImovel'].' no '.$item['Bairro'].', '.$item['Cidade'];
                $valor = $Numbers::decimalize($valor);
                $valorCondominio = $Numbers::decimalize($valorCondominio);

                if ( !isset($acao) )
                    continue;

                $formated[$i]['token'] = str_random(40);
                $formated[$i]['estate_id'] = $this->estate_id;
                $formated[$i]['origem'] = $this->integracao;
                $formated[$i]['codigo_custom'] = @$item['CodigoImovel'];
                $formated[$i]['codigo'] = $Numbers::random('realestate');
                $formated[$i]['titulo'] = $titulo;
                $formated[$i]['acao'] = $acao;
                $formated[$i]['tipo'] = 'mude_ja';
                $formated[$i]['imovel'] = $item['TipoImovel'];
                $formated[$i]['endereco'] = $item['Endereco'];
                $formated[$i]['cidade'] = $item['Cidade'];
                $formated[$i]['bairro'] = $item['Bairro'];
                $formated[$i]['cep'] = $item['CEP'];
                $formated[$i]['numero'] = $item['Numero'];
                $formated[$i]['valor'] = $valor;
                $formated[$i]['valor_condominio'] = $valorCondominio;
                $formated[$i]['area_util_m2'] = $Numbers::decimalize($item['AreaUtil']);
                $formated[$i]['area_total_m2'] = $Numbers::decimalize($item['AreaTotal']);
                $formated[$i]['obs'] = $item['Observacao'];
                //$formated[$i]['TipoOferta'] = $item['TipoOferta'];
                $formated[$i]['qtd_pessoas_temporada'] = @$item['QtdPessoasTemporada'];
                $formated[$i]['qtd_andares'] = $item['QtdAndar'];
                $formated[$i]['qtd_apto_por_andar'] = $item['QtdUnidadesAndar'];
                $formated[$i]['mostrar_mapa'] = 'mostrar-regiao';

                $caract = [];
                $caract['Quartos'] = $item['QtdDormitorios'];
                $caract['Vagas de Garagem'] = $item['QtdVagas'];
                $caract['Suítes'] = $item['QtdSuites'];
                $caract['Churrasqueira'] = $item['Churrasqueira'];
                $caract['Banheiro'] = $item['QtdBanheiros'];
                $caract['Sala de TV'] = $item['QtdSalas'];
                $caract['Elevadores Social'] = $item['QtdElevador'];
                $caract['Piscina Externa'] = $item['Piscina'];
                $caract['Sauna'] = $item['Sauna'];

                $formated[$i]['caracteristicas'] = $caract;

                $fotos = [];
                if ( isset($item['Fotos']) ) {
                    foreach ( $item['Fotos'] as $_fotos ) {

                        if ( isset($_fotos['URLArquivo']) )
                            $fotos[] = $_fotos['URLArquivo'];

                        foreach ( $_fotos as $foto ) {
                            if ( isset($foto['URLArquivo']) )
                                $fotos[] = $foto['URLArquivo'];
                        }
                    }
                }

                $formated[$i]['fotos'] = $fotos;

                $i++;

            }
        }


        return $formated;
    }

    public function save()
    {
        if ( !$this->data || !count($this->data) )
            throw new \InvalidArgumentException('Data invalido!');

        $Upload = new \lslucas\Files\Upload();
        $sum = 0;

        foreach ( $this->data as $item ) {
            $estate_id = $this->store($item);

            if ( isset($item['fotos']) && $this->importPhotos ) {
                foreach ( $item['fotos'] as $foto ) {
                    //$Upload->start(trim($foto), ['area' => 'realestate', 'token' => $item['token'], 'codigo' => $item['codigo']]);
                    $Upload->mocking(trim($foto), ['area' => 'realestate', 'token' => $item['token'], 'codigo' => $item['codigo']]);
                }
            }

            $sum++;
        }

        return $sum;
    }

    protected function store($request)
    {
        $RealEstate = new RealEstateController();

        $self = new RealEstate();

        $RealEstate->insertUpdate($self, $request);

        return $self->id;
    }

    public function importPhotos($bool=true)
    {
        $this->importPhotos = $bool;

        return;
    }

    public function estate_id($id=null)
    {
        $this->estate_id = $id;

        return;
    }


}
