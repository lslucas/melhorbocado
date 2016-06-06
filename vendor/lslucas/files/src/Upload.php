<?php namespace lslucas\Files;

use Dflydev\ApacheMimeTypes\PhpRepository;
use App\StorageTable;
use Illuminate\Http\Response;
use \Image;
use \Storage;
use \Carbon;

class Upload {

    protected $disk,
    $mimeDetect;

    public function __construct()
    {
        $this->disk = Storage::disk();
        $this->mimeDetect = new PhpRepository();
    }

    public function mocking($file, $request)
    {
        $token = isset($request['token']) ? $request['token'] : csrf_token();
        $originalName = basename($file);
        $filename = camel_case(basename($file));

        if ( !$file )
            return 'File invalid';

        $pFile = new StorageTable();

        if ( $token )
            $pFile->idrel = $token;
        if ( isset($request['area']) )
            $pFile->area = $request['area'];

        $pFile->file = $file;
        $pFile->name = $filename;
        $pFile->original_name = $originalName;
        $pFile->save();

        return $pFile->id;
    }

    public function start($file, $request)
    {
        $path = 'storage';
        $token = isset($request['token']) ? $request['token'] : csrf_token();

        if ( isset($request['area']) && strlen($request['area'])>1 )
            $path = $request['area'];

        if ( isset($request['path']) && strlen($request['path'])>1 )
            $path = $request['path'];


        if ( is_string($file) ) {

            $fileUrl = $file;
            $fileContents = $this->curl($file);
            $originalName = $this->fixFilename($fileUrl);

        } else {

            $originalName = $file->getClientOriginalName();
            $fileRaw      = $file->move('../storage/app/tmp');
            $fileContents = file_get_contents($fileRaw->getRealPath());

            $delete = \File::delete('../storage/app/tmp/'.$fileRaw->getFilename());

        }

        $filename = camel_case($originalName);
        if ( isset($request['codigo']) ) {
            $filepath = $path .'/' . $request['codigo'] . '/' . $filename;

        } else {
            $filename = str_random(6).'-'.$filename;
            $filepath = $path .'/' . $filename;
        }

        #chmod(storage_path().'/app/'.dirname($filepath), 0777);
        $file = $this->disk->put($filepath, $fileContents);

        if ( !$file )
            return 'error';

        $pFile = new StorageTable();

        if ( $token )
            $pFile->idrel = $token;
        if ( isset($request['area']) )
            $pFile->area = $request['area'];

        $pFile->file = $filepath;
        $pFile->name = $filename;
        $pFile->original_name = $originalName;
        $pFile->save();

        return $pFile->id;
    }

    public function remove($fileid)
    {
        $file = StorageTable::find($fileid);

        if ( !$file ) {
            $file = StorageTable::where('file', '=', $fileid)->first();
        }

        if ( !$file ) {
            echo $fileid. ' não existe.';
            return false;
        }

        $this->disk->delete($file->file);
        $file->delete();

        echo $file->name. ' apagado.';
        return true;
    }

    public function get($filename)
    {
        try {

            $entry = StorageTable::where('file', '=', $filename)->firstOrFail();
            $fileDetails = $this->fileDetails($entry->file);
            $filepath = storage_path('app/'.$filename);
            $response = response(file_get_contents($filepath), 200)->header('Content-Type', $fileDetails['mimeType']);

            return $response;

        } catch (\Exception $e) {

            exit('Arquivo '.$filename.' não existe.');

        }
	}

    /**
    * Return an array of file details for a file
    */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path),
        ];
    }

    /**
    * Return the full web path to a file
    */
    public function fileWebpath($path)
    {
        $path = rtrim(config('storage.app'), '/') . '/' .  ltrim($path, '/');
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
        return $this->disk->size($path);
    }

    /**
    * Return the last modified time
    */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            $this->disk->lastModified($path)
        );
    }

    public function curl($URL){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function fixFilename($str, $fixExt='xml')
    {
        $str = basename($str);

        // check if filename has .$fixExt
        if ( strpos($str, $fixExt)!==false )
            $filename = $str;
        else
            $filename = str_random(6);

        $filename = strtok($filename, '?');

        if( strpos($str, '&') )
            $filename = strtok($filename, '&');

        if( !strpos($str, '.'.$fixExt) )
            $filename .= '.'.$fixExt;

        return $filename;
    }
}
