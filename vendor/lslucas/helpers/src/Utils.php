<?php namespace lslucas\Helpers;

class Utils {

    public function is_external($url) {
        $components = parse_url($url);

        return (bool) !empty($components['host']) && strcasecmp($components['host'], config('url'));
    }

}
