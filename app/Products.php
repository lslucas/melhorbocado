<?php namespace App;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use \lslucas\Helpers;
use \lslucas\Custom;
use \lslucas\Files;

use App\Products;
use App\Category;

class Products extends Model {

	protected $table = 'products';

	public static function listing($request, $autor)
	{
        $cache = new Helpers\CacheTable();

        $base = substr($request->getPathInfo(), 1);

        $query  = \Input::has('filtro') ? array_filter(\Input::get('filtro')) : [];
        $q      = \Input::has('q') ? array_filter(\Input::get('q')) : false;
        $codigo = \Input::has('codigo') ? trim(\Input::get('codigo')) : false;

        $query  += [
			'autor' => $autor,
            'area' => \Input::get('area'),
            'subcategoria' => \Input::get('subcategoria'),
            'q' => $q,
            'signo' => \Input::get('signo'),
            'palavrachave' => \Input::get('palavrachave'),
        ];

        $ordenarPor = isset($_POST['ordenar-por']) ? $_POST['ordenar-por'] : false;

		if ( $ordenarPor && in_array($ordenarPor, ['mais_recente', 'mais_antigo']) ) {
			$sortBy = 'data';

			if ( \Input::get('ordenar-por')=='mais_recente' )
				$sortOrder = 'ASC';
			else
				$sortOrder = 'DESC';

		} else {
            $sortBy = $sortOrder = 'RAND()';

		}

		// QUERY
		$totalItems = $cache->total(['destaque'=>0]+$query, new Posts);
		$totalDestaques = $cache->total(['destaque'=>1]+$query, new Posts);

		// Fake pagination
		$total = $totalItems+$totalDestaques;
		$perPage = 8;
		$destaquesPerPage = 5;
		$item = 1;
		$page = 1;
		$curPage = \Input::get('page') ?: 1;

		$totalPages = ceil($total/$perPage);
		$totalDestaquesPages = ceil($totalDestaques)/$destaquesPerPage;

		$fakePage = [];

		// Aqui listamos a lista mostrando sempre $destaquesPerPage primeiros
		// destaques e depois os demais imóveis por página
		//do {

			$maxImoveisDestaque = $destaquesPerPage;
			$numImovelDestaque  = 0;

			if ( $curPage<=$totalDestaquesPages ) {

				$destaqueSkip = $curPage==1 ? 0 : ($curPage-1)*$destaquesPerPage;
				$destaqueTake = $curPage==1 ? $destaquesPerPage : $destaqueSkip+$destaquesPerPage;

				// adiciona os imoveis destaques
                $destaque = $cache->ofQuery(['destaque'=>1]+$query, new Posts, [$destaqueSkip, $destaqueTake], [$sortBy, $sortOrder]);
				foreach ( $destaque as $spotlight ) {

					// max de $destaquesPerPage destaques
					if ( $numImovelDestaque==$destaquesPerPage )
						break;

					$fakePage[] = $spotlight;
					unset($spotlight);
					$item++;
					$numImovelDestaque++;
				}

			}

			$maxImoveisComuns = $perPage-$numImovelDestaque;
			$numImovelComum   = 0;
			$skip = $curPage==1 ? 0 : ($curPage-1)*$maxImoveisComuns;
			$take = $curPage==1 ? $maxImoveisComuns : $skip+$maxImoveisComuns;

			// adiciona os imoveis comuns
            $items = $cache->ofQuery(['destaque'=>0]+$query, new Posts, [$skip, $take], [$sortBy, $sortOrder]);
			foreach ( $items as $posts ) {

				// max de $destaquesPerPage destaques
				if ( $numImovelComum==$maxImoveisComuns )
					break;

				$fakePage[] = $posts;
				unset($posts);
				$item++;
				$numImovelComum++;
			}

			if ( $item==8 )
				$page++;

		//} while ( $page<$totalPages );

		$collection = new Collection($fakePage);
		$pagination = new LengthAwarePaginator(
		  $collection,
		  $total,
		  $perPage,
		  $curPage,
		  [
			  'path' => '/'.$base
		  ]
	  	);

		return $pagination;
	}

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope of query, the main filter of the search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfQuery($query, $q)
    {
        $status = isset($q['status']) ? $q['status'] : false;

        // se codigo for informado, não é necessário filtrar outros parametros
        if ( isset($q['id']) && !empty($q['id']) ) {
            $query->where(function($qry) use ($q) {
                $qry->orWhereRaw('id=?', [$q['codigo']]);
            });

        } else {

            if ( $status )
                $query->whereRaw('status=?', [$status]);

			if ( isset($q['destaque']) && ($q['destaque']===1 || $q['destaque']===0) )
                $query->whereRaw('destaque=?', [$q['destaque']]);

			if ( isset($q['subcategoria']) && $q['subcategoria'] ) {
                if ( is_array($q['subcategoria']) )
                    $query->whereIn('subcategoria', $q['subcategoria']);
                else
                    $query->whereRaw('subcategoria=?', [$q['subcategoria']]);
            }

			if ( isset($q['area']) && $q['area'] ) {
                if ( is_array($q['area']) )
                    $query->whereIn('area', $q['area']);
                else
                    $query->whereRaw('area=?', [$q['area']]);
            }

			if ( isset($q['autor']) && $q['autor'] ) {
                if ( is_array($q['autor']) )
                    $query->whereIn('autor', $q['autor']);
                else
                    $query->whereRaw('autor=?', [$q['autor']]);
            }

            if ( isset($q['titulo']) )
                $query->whereRaw('titulo LIKE ?', ['%'.$q['acao'].'%']);

            if ( isset($q['data']) && !isset($q['data_ate']) || isset($q['data_ate']) && !$q['data_ate'] )
                $query->whereRaw('data=?', $q['data']);
            elseif ( isset($q['data']) && isset($q['data_ate']) )
                $query->whereRaw('data BETWEEN ? AND ?', $q['data'], $q['data_ate']);

            if ( isset($q['listado']) && $q['listado'] )
                $query->whereRaw('listado=?', $q['listado']);

            if ( isset($q['q']) ) {

                if ( is_array($q['q']) ) {

                    $query->where(function($qry) use ($q) {
                        foreach ( $q['q'] as $caracteristica ) {
                            $qry->orWhereRaw('titulo LIKE ?', ['%'.$caracteristica.'%']);
                            $qry->orWhereRaw('texto LIKE ?', ['%'.$caracteristica.'%']);
                            $qry->orWhereRaw('palavrachave LIKE ?', ['%'.$caracteristica.'%']);
                            $qry->orWhereRaw('linhafina LIKE ?', ['%'.$caracteristica.'%']);
                        }
                    });

                }
            }
        }

        return $query;
    }

    /**
     * Nome da area
     *
     * @return string
     */
    public function area()
    {
        return Category::name($this->area);
    }


    /**
     * Nome da subcategoria
     *
     * @return string
     */
    public function subcategoria()
    {
        return Category::name($this->subcategoria);
    }

    /**
     * Monta url do imovel atual
     *
     * @return string
     */
    public function url()
    {
        if ( !isset($this->titulo) )
            return false;

        return '/post/'. $this->id .'/'. str_slug($this->titulo, '-');
    }

    /**
     * Resgata fotos do item atual
     *
     * @return array|string (cover)
     */
    public function fotos($onlyCover=false, $size='home', $limit=4)
    {
        if ( !@$this->token )
            return false;

        $retrieve = new Files\Retrieve();

        $cover = $retrieve->cover($this->token, $size);

        if ( $onlyCover )
            return $cover;

		if ( $size=='noresize' )
        	return $retrieve->fromStorage($this->token, 'noresize', $limit);

        $thumb = $retrieve->fromStorage($this->token, 'thumb');
        $medium = $retrieve->fromStorage($this->token, 'medium');
        $large = $retrieve->fromStorage($this->token, 'large');

        $data = array_merge($thumb, $medium, $large);

        return $data;
    }

}
