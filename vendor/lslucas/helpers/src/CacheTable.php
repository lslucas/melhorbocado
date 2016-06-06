<?php namespace lslucas\Helpers;

use Cache;
use App;

class CacheTable {

    public function flush()
    {
        return Cache::flush();
    }

    public function total($query, $model, $timeout=120)
    {
        list($label, $arr) = $this->warmUp($query, $model);

        $total = Cache::remember(md5('total='.$label), $timeout, function() use ($arr) {
            return $arr['model']::ofQuery($arr['query'])->count();
        });

        if ( !$total || !is_numeric($total) )
            return 0;

        return $total;
    }

    public function ofQuery($query, $model, $take=false, $sort=false, $timeout=30)
    {
        list($label, $arr) = $this->warmUp($query, $model, $take, $sort);

        return Cache::remember(md5('ofQuery='.$label), $timeout, function() use ($arr) {

            $data = $arr['model']::ofQuery($arr['query']);

            if ( isset($arr['sort']) && $arr['sort'][0]=='RAND()' )
                $data->orderByRaw('RAND()');
            elseif ( isset($arr['sort']) && $arr['sort'][0]!='RAND()' )
                $data->orderBy($arr['sort'][0], $arr['sort'][1]);

            if ( isset($arr['take']) )
                $data->skip($arr['take'][0]);

            if ( isset($arr['take']) )
                $data->take($arr['take'][1]);

            return $data->get();
        });
    }

    public function raw($sql, $timeout = 60) {
        return Cache::remember(md5($sql), $timeout, function() use ($sql) {
            return DB::raw($sql);
        });
    }

    public function warmUp($query, $model, $take=false, $sort=false)
    {
        if ( !is_string($query) )
            $label = serialize($query);

        $arr = [
            'model' => $model,
            'query' => $query,
            'take' => $take,
            'sort' => $sort

        ];

        return [$label, $arr];
    }

}
