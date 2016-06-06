<?php namespace lslucas\Integracoes;

use \lslucas\Files;
use \lslucas\Helpers;
use Nathanmac\Utilities\Parser\Parser;
use App\Http\Controllers\Admin\RealEstateController;
use App\RealEstate;

use Illuminate\Http\Request;

class Inforce {

    public $storage_id = null, $data = null, $integracao = 'Inforce', $importPhotos = true, $estate_id = null;

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

        $this->data = $this->format($parsed);

        return $this->data;
    }

    public function format($payload)
    {
        $Numbers = new Helpers\Numbers();

        $formated = [];

        foreach ( $payload as $imoveis ) {

            $i=0;
            if ( !is_array($imoveis))
                continue;

            foreach ( $imoveis as $item ) {

                if ( isset($item['PrecoVenda']) && $item['PrecoVenda'] ) {
                    $acao = 'venda';
                    $valor = $item['PrecoVenda'];

                } elseif ( isset($item['PrecoLocacao']) && $item['PrecoLocacao'] ) {
                    $acao = 'locacao';
                    $valor = $item['PrecoLocacao'];

                } elseif ( isset($item['PrecoLocacaoTemporada']) && $item['PrecoLocacaoTemporada'] ) {
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

                if ( (int)$item['ProntoMorar']==1 )
                    $formated[$i]['tipo'] = 'mude_ja';

                $formated[$i]['acao'] = $acao;
                $formated[$i]['imovel'] = $item['TipoImovel'];
                $formated[$i]['endereco'] = $item['Endereco'];
                $formated[$i]['cidade'] = $item['Cidade'];
                $formated[$i]['bairro'] = $item['Bairro'];
                $formated[$i]['cep'] = $Numbers::onlyNumbers($item['CEP']);
                $formated[$i]['numero'] = $item['Numero'];
                $formated[$i]['valor'] = $valor;
                $formated[$i]['valor_condominio'] = $valorCondominio;
                $formated[$i]['area_util_m2'] = $Numbers::decimalize($item['AreaUtil']);
                $formated[$i]['area_total_m2'] = $Numbers::decimalize($item['AreaTotal']);
                $formated[$i]['obs'] = $this->parseObservacao($item['Observacao']);
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
                $caract['Playground'] = $item['Playground'];
                $caract['Salão de Festas'] = $item['SalaoFestas'];
                $caract['Portaria 24 horas'] = $item['Acesso24Horas'];
                $caract['Ar Condicionado'] = $item['ArCondicionado'];
                $caract['Área de Serviço'] = $item['AreaServico'];
                $caract['Armários de Cozinha'] = $item['ArmarioCozinha'];
                $caract['Armários Embutidos'] = $item['ArmarioEmbutido'];
                $caract['Business Center'] = $item['BusinessCentar'];
                $caract['Campo de Futebol'] = $item['CampoFutebol'];
                $caract['Casa de Alvenaria'] = $item['CasaAlvenaria'];
                $caract['Casa do Caseiro'] = $item['CasaCaseiro'];
                $caract['Casa de Fundo'] = $item['CasaFundo'];
                $caract['Casa de Madeira'] = $item['CasaMadeira'];
                $caract['Casa Mista'] = $item['CasaMista'];
                $caract['Caseiro'] = $item['Caseiro'];
                $caract['Cerca'] = $item['Cerca'];
                $caract['Children Care'] = $item['ChildrenCare'];
                $caract['Churrasqueira'] = $item['Churrasqueira'];
                $caract['Closet'] = $item['Closet'];
                $caract['Clube'] = $item['Clube'];
                $caract['CofeeShop'] = $item['CoffeeShop'];
                $caract['Copa'] = $item['Copa'];
                $caract['Cozinha Azulejada'] = $item['CozinhaAzulejada'];
                $caract['Curral'] = $item['Curral'];
                $caract['Despensa'] = $item['Despensa'];
                $caract['Escritório'] = $item['Escritorio'];
                $caract['Esgoto'] = $item['Esgoto'];
                $caract['Esquina'] = $item['Esquina'];
                $caract['Estacionamento'] = $item['EstacionamentoRotativo'];
                $caract['Estacionamento para Visitantes'] = $item['EstacionamentoVisitantes'];
                $caract['Vista para o Mar'] = $item['FrenteMar'];
                $caract['Guarita'] = $item['Guarita'];
                $caract['Heliponto'] = $item['Heliponto'];
                $caract['Hidromassagem'] = $item['Hidromassagem'];
                $caract['HomeTheater'] = $item['HomeTheater'];
                $caract['Internet'] = $item['InfraInternet'];
                $caract['Interfone'] = $item['Interfone'];
                $caract['Jardim'] = $item['Jardim'];
                $caract['Lago'] = $item['Lago'];
                $caract['Lareira'] = $item['Lareira'];
                $caract['Lavanderia'] = $item['LavaRoupas'];
                $caract['Lavanderia Coletiva'] = $item['LavanderiaColetiva'];
                $caract['Lavoura'] = $item['Lavoura'];
                $caract['Marina'] = $item['Marina'];
                $caract['Mobiliado'] = $item['Mobiliado'];
                $caract['Paiol'] = $item['Paiol'];
                $caract['Pasto'] = $item['Pasto'];
                $caract['Pier'] = $item['Pier'];
                $caract['Piscina'] = $item['Piscina'];
                $caract['Piso Elevado'] = $item['PisoElevado'];
                $caract['Pista de Bicicross'] = $item['PistaBicicross'];
                $caract['Pista de Cooper'] = $item['PistaCooper'];
                $caract['Pista de Pouso'] = $item['PistaPouso'];
                $caract['Pista de Skate'] = $item['PistaSkate'];
                $caract['Poço'] = $item['Poco'];
                $caract['Poço Artesiano'] = $item['PocoArtesiano'];
                $caract['Ponte Rolante'] = $item['PonteRolante'];
                $caract['Porteira Fechada'] = $item['PorteiraFechada'];
                $caract['Qtd de Pessoas por Temporada'] = $item['QtdPessoasTemporada'];
                $caract['Quadra de Squash'] = $item['QuadraSquash'];
                $caract['Quadra de Tênis'] = $item['QuadraTenis'];
                $caract['Quadra PoliEsportiva'] = $item['QuadraPoliEsportiva'];
                $caract['Quintal'] = $item['Quntal'];
                $caract['Rede de Telefone'] = $item['RedeTelefone'];
                $caract['Refeitorio'] = $item['Refeitorio'];
                $caract['Reservatório de Água'] = $item['ReservatorioAgua'];
                $caract['Restaurante'] = $item['Restaurante'];
                $caract['Rio'] = $item['Rio'];
                $caract['Roupa de Banho'] = $item['RoupaBanho'];
                $caract['Roupa de Cama'] = $item['RoupaCama'];
                $caract['Roupa de Mesa'] = $item['RoupaMesa'];
                $caract['Rua Asfaltada'] = $item['RuaAsfaltada'];
                $caract['Sala de Jantar'] = $item['SalaJantar'];
                $caract['Sala de Ginástica'] = $item['SalaGinastica'];
                $caract['Sala de Íntima'] = $item['SalaIntima'];
                $caract['Salão de Jogos'] = $item['SalaoJogos'];
                $caract['Cinema'] = $item['SalaVideo'];
                $caract['Segurança Interna'] = $item['SegurancaInterna'];
                $caract['Segurança de Rua'] = $item['SegurancaRua'];
                $caract['Segurança de Patrimonial'] = $item['SegurancaPatrimonial'];
                $caract['Sistema de Incêndio'] = $item['SistemaIncendio'];
                $caract['Solarium'] = $item['Solarium'];
                $caract['Descanso com Ofurô e Spa'] = $item['SpaHidromassagem'];
                $caract['Telefone'] = $item['Telefone'];
                $caract['Terraco'] = $item['Terraco'];
                $caract['TV'] = $item['TV'];
                $caract['TV à Cabo'] = $item['TVCabo'];
                $caract['Utensilios de Cozinha'] = $item['UtensiliosCozinha'];
                $caract['Utilize FGTS'] = $item['UtilizeFGTS'];
                $caract['Varanda'] = $item['Varanda'];
                $caract['Vidros Reflexivos'] = $item['VidrosReflexivos'];
                $caract['Visite Decorado'] = $item['VisiteImovelDecorado'];
                $caract['Banheiro de Serviço'] = $item['WCEmpregada'];

                $formated[$i]['caracteristicas'] = $caract;

                $fotos = [];
                foreach ( $item['Fotos'] as $_fotos ) {

                    if ( isset($_fotos['URLArquivo']) )
                        $fotos[] = $_fotos['URLArquivo'];

                    foreach ( $_fotos as $foto ) {
                        if ( isset($foto['URLArquivo']) )
                            $fotos[] = $foto['URLArquivo'];
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

    public function parseObservacao($obs=null)
    {
        if ( !$obs )
            return null;

        if ( !is_array($obs) )
            return $obs;

        else {

            $res = "<ul>";

            foreach ( $obs as $row ) {
                if ( !$row )
                    continue;

                $res .= "<li>".nl2br(html_entity_decode(trim($row)))."</li>";
            }

            $res .= "</ul>";

        }

        return $res;
    }

}
