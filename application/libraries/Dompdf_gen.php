<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Jika menggunakan Composer:
// require_once APPPATH . 'vendor/autoload.php'; // Path ke autoload Composer

// Jika tidak menggunakan Composer:
// Pastikan path ini sesuai dengan struktur folder Dompdf yang diunduh
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_gen
{
    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    public function load_html($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename = 'document.pdf', $options = array())
    {
        $this->dompdf->stream($filename, $options);
    }
}
