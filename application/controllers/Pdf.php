<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('C:\xampp\htdocs\sw\application\libraries\Fpdf.php');

class Pdf extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->SetFont('Arial', 'B', 12);
        $this->Image('C:\xampp\htdocs\sw\assets\backend\img\Header.png', 49, 5, 160, 30);
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        // $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Image('C:\xampp\htdocs\sw\assets\backend\img\Footer.png', 0, 280, 210, 5);
    }

    public function __construct()
    {
        parent::__construct();
    }
}
