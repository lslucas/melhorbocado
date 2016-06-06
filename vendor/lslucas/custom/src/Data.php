<?php namespace lslucas\Custom;

use App\RealEstate;
use DB;

class Data {

    public static function pais()
    {
        $paises = DB::table('pais')->get();

        if ( !$paises )
            return false;

        $res    = [];

        foreach ($paises as $row) {
            $res[] = $row->nome;
        }

        return $res;
    }

    public static function acao($option=null)
    {
        $data = [
            'venda' => 'Venda',
            'locacao' => 'Locação',
            'temporada' => 'Temporada',
            'internacional' => 'Internacional',
        ];

        return $option===null ? $data : ( isset($data[$option]) ? $data[$option] : null );
    }

    public static function imovel($option=null)
    {
        $data = [
            'casa' => 'Casa',
            'apartamento' => 'Apartamento',
            'duplex' => 'Duplex',
            'condominio' => 'Condomínio',
            'kitnet' => 'Kitnet/Studio',
            'escritorio' => 'Sala/Escritório',
            'loja' => 'Loja',
            'terreno' => 'Terreno',
            'outro' => 'Outro',
        ];

        return $option===null ? $data : ( isset($data[$option]) ? $data[$option] : null );
    }

    public static function classificacao($option=null)
    {
        $data = [
            'Apartamento',
            'Cobertura',
            'Casa',
            'Comercial',
            'Terrea',
            'Linear',
            'Sala',
            'Lote',
            'Chácara',
            'Duplex',
            'Laje',
            'Casa em Condomínio',
            'Lote em Condomínio',
            'Sítio',
            'Loft/Flat',
            'Triplex',
            'Loja',
            'Área',
            'Fazenda',
            'Prédio',
            'Outro'
        ];

        return $option===null ? $data : ( isset($data[$option]) ? $data[$option] : null );
    }

    public static function caracteristicas($option=null)
    {

        $data = [
            'Quartos' => 'int',
            'Suítes' => 'int',
            'Closet' => 'int',
            'Sala de Estar',
            'Banheiro' => 'int',
            'Copa',
            'Sala de Jantar',
            'Sala de TV',
            'Escritório' => 'int',
            'Área de Serviço',
            'Despensa',
            'Lavabo',
            'Sacada ou Varanda' => 'int',
            'Churrasqueira',
            'Terraço com Churrasqueira',
            'Dep. Empregada',
            'Banheiro de Serviço' => 'int',
            'Depósito',
            'Mobiliado',
            'Quintal',
            'Vagas de Garagem' => 'int',
            'Estacionamento',
            'Piso Laminado',
            'Piso Frio',
            'Piso de Madeira',
            'Luminárias',
            'Armários de Cozinha',
            'Armários Embutidos',
            'Face do Imóvel' => ['norte'=>'Norte', 'sul'=>'Sul', 'leste'=>'Leste', 'oeste'=>'Oeste'],
            'Vista para o Mar',
            'Hall Social Independente',
            'Elevadores Social' => 'int',
            'Elevadores de Serviço' => 'int',
            'Playground',
            'Play Aventura / Zoo',
            'Pista de Cooper',
            'Piscina Coberta Aquecida',
            'Piscina Externa',
            'Piscina Infantil',
            'Saúna eca e Úmida',
            'Quadra Poliesportiva',
            'Salão de Jogos e Games',
            'Salão de Festas',
            'Salão de Fitness',
            'Forno de Pizza',
            'Cinema',
            'Garage Band',
            'Quadra de Tênis',
            'Quadra de Squash',
            'Hidromassagem',
            'Descanso com Ofurô e Spa',
            'Sauna',
            'Children Care',
            'Brinquedoteca',
            'Ateliê',
            'Pet Play',
            'Pergolado',
            'Gazebos',
            'Jardins',
            'Praças',
            'Espelhos D´Água',
            'Cascata',
            'Zeladoria',
            'Gerador',
            'Cerca Elétrica',
            'Câmeras / CFTV',
            'Portão Eletrônico',
            'Segurança 24 horas',
            'Portaria 24 horas',
            'Vestiário',
        ];

        if ( $option=='array' ) {
            $res = [];
            foreach ( $data as $d=>$v ) {
                $f = is_numeric($d) ? $v : $d;
                array_push($res, $f);
            }

            asort($res);

            return $res;
        }

        return $option===null ? $data : ( isset($data[$option]) ? $data[$option] : null );
    }

    public static function qtdOfertas($base)
    {
        $query = [];
        if ( $base=='comprar' )
            $acao = 'venda';
        if ( $base=='alugar' )
            $acao = 'locacao';
        if ( $base=='temporada' )
            $acao = 'temporada';

        if ( isset($acao) )
            $query = ['acao' => $acao];

        $num = RealEstate::ofQuery($query)->count();

        return $num;
    }
}
