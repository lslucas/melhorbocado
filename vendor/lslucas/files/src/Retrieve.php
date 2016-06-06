<?php namespace lslucas\Files;

use Dflydev\ApacheMimeTypes\PhpRepository;
use App\StorageTable;
use Illuminate\Http\Response;
use \lslucas\Helpers\Utils;
use \lslucas\Helpers\File;
use \lslucas\Helpers\CacheTable;
use \Image;
use \Storage;
use \Carbon;

class Retrieve {

    protected $disk,
        $mimeDetect;
    public $utils=false, $file=false;

    public function __construct()
    {
        $this->disk = Storage::disk();
        $this->mimeDetect = new PhpRepository();
        $this->utils = new Utils();
        $this->file = new File();
    }

    public function find($id)
    {
        $data = StorageTable::find($id);

        return $data;
	}

    public function getContents($id)
    {
        $fileData = $this->find($id);

        if ( !$fileData )
            return false;

        $filename = $fileData->file;

        $filepath = storage_path('app/'.$filename);

        $contents = file_get_contents($filepath);

        return $contents;
	}

    public function get($filename)
    {
        $filename = $this->fixUrl($filename);

        try {

            $fileDetails = $this->fileDetails($filename);
            $filepath = storage_path('app/'.$filename);
            $response = response(file_get_contents($filepath), 200)->header('Content-Type', @$fileDetails['mimeType']);

            return $response;

        } catch (\Exception $e) {

            exit('Arquivo '.$filename.' não existe.');

        }
	}

    public function resize($filename, $args=[])
    {
        $filename = $filePath = $this->fixUrl($filename);


        try {

            $fileDetails = $this->fileDetails($filename, true);
            $path = $fileDetails['external'] ? '/cache' : $fileDetails['onlyPath'];
            $newPath = $this->newPath($path, $args);

            $fileNewPath = $newPath.$fileDetails['name'];

            if ( isset($args['resize']) ) {

                if ( !$this->disk->exists($fileNewPath) ) {

                    $img = Image::make($filePath);
                    $img->resize(@$args['resize'][0], @$args['resize'][1], function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $this->disk->put($fileNewPath, $img->encode());

                }

                return '/storage'.$fileNewPath;

            }


        } catch (\Exception $e) {

            return false;
            //return $filePath;

        }
	}

    public function fromStorage($idrel, $returnType='default', $limit=false, $args=[])
    {
        if ( $limit )
            $data = StorageTable::where('idrel', '=', $idrel)->limit($limit)->get();
        else
            $data = StorageTable::where('idrel', '=', $idrel)->get();

        $thumbs = array();

        foreach ( $data as $item ) {

            $resize = [null, 120];
            if ( $returnType == 'thumb' || $returnType == 'noresize' )
                $resize = [null, 210];
            if ( $returnType == 'medium' )
                $resize = [null, 415];
            if ( $returnType == 'large' )
                $resize = [1024, null];

            if ( env('APP_ENV')=='local' )
                $file = $item->file;
            else
                $file = $this->resize($item->file, ['resize' => $resize]);

            $thumbs[] = [
                'id' => $item->id,
                'name' => $item->name,
                'file' => $item->file,
                $returnType => $file ? $file : '/storage/'.$item->file,
                'original' => $this->utils->is_external($item->file) ? $item->file : '/storage/'.$item->file
            ];

        }

        return $thumbs;
    }

    public function cover($idrel, $args=[])
    {
        $item = StorageTable::where('idrel', '=', $idrel)->first();

        if ( !$item )
            return false;

        if ( !is_array($args) ) {
            switch ($args) {
                case 'home': $args = [null, 252];
                break;
                case 'list': $args = [null, 252];
                break;
                case 'show': $args = [400, null];
                break;
                case 'big': $args = [1024, null];
                break;
            }
        }

        $width  = isset($args[0]) ? $args[0] : null;
        $height = isset($args[1]) ? $args[1] : null;

        if ( env('APP_ENV')=='local' )
            return $item->file;
        else
            return $this->resize($item->file, ['resize' => [$width, $height]]);
    }

    protected function newPath($path, $args)
    {
        $newDir = join($args['resize'], 'x');
        if ( substr($newDir, 0, 1)=='x' )
            $newDir = substr($newDir, 1);
        if ( substr($newDir, -1)=='x' )
            $newDir = substr($newDir, 0, -1);

        return $path.'/resized/'.$newDir.'/';
    }

    /**
    * Return an array of file details for a file
    */
    protected function fileDetails($path, $basic=false)
    {
        if ( !$this->utils->is_external($path) )
            $path = '/' . ltrim($path, '/');

        $name = basename($path);
        $onlyPath = str_replace('/'.$name, '', $path);
        $external = (bool) $this->utils->is_external($path);

        $res = [
            'name' => $name,
            'external' => $external,
            'fullPath' => $path,
            'onlyPath' => $onlyPath,
        ];

        if ( !$basic )
            $res = $res+[
                'webPath' => !$external ? $this->fileWebpath($path) : false,
                'mimeType' => $this->fileMimeType($path),
                'size' => !$external ? $this->fileSize($path) : false,
                'modified' => !$external ? $this->fileModified($path) : false,
            ];

        return $res;
    }

    /**
    * Return the full web path to a file
    */
    public function fileWebpath($path)
    {
        if ( $this->utils->is_external($path) ) {
            $path = dirname($path);
        } else {
            $path = rtrim(config('storage.app'), '/') . '/' .  ltrim($path, '/');
        }

        return url($path);
    }

    /**
    * Return the mime type
    */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
    * Return the file size
    */
    public function fileSize($path)
    {
        if ( $this->utils->is_external($path) )
            return $this->file->remote_filesize($path);
        else
            return $this->disk->size($path);
    }

    /**
    * Return the last modified time
    */
    public function fileModified($path)
    {
        $t = $this->disk->lastModified($path);
        return Carbon::createFromTimestamp( $t );
    }

    public function fixUrl($filename)
    {
        if ( $this->utils->is_external($filename) )
            $filePath = $filename;
        else {
            if ( $this->disk->exists($filename) )
                $filePath = $filename;
            // localhost, entao força ser url externa pra mostrar as imagens do vivonorio.com.br
            else
                $filePath = 'http://vivonorio.com.br/storage/'.$filename;
        }

        return $filePath;
    }

}
