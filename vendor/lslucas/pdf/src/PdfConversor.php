<?php namespace lslucas\PDF;

require_once 'vendor/lslucas/pdf/vendor/dompdf/dompdf/dompdf_config.inc.php';
require_once('vendor/lslucas/pdf/vendor/dompdf/dompdf/include/autoload.inc.php');
require_once('vendor/lslucas/pdf/vendor/phenx/php-font-lib/classes/Font.php');

class PdfConversor {

    public function __construct()
    {
        //
    }

    // Converte um caminho de view para html
    public function convert($htmlPath, $pdfPath, $args=[])
    {
        $dompdf = new \DOMPDF();

        $dompdf->load_html($htmlPath);
        if ( isset($args['paper']) )
            $dompdf->set_paper($args['paper'][0], $args['paper'][1]);
        $dompdf->render();

        file_put_contents($pdfPath, $dompdf->output());

        if ( isset($args['onlyConvert']) )
            return $pdfPath;
        else
            return $dompdf->stream($pdfPath, array("Attachment" => false));

        return true;
    }

}
