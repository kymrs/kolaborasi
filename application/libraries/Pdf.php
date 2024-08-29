<?php
defined('BASEPATH') or exit('No direct script access allowed');

require('fpdf/fpdf.php');

class Pdf extends FPDF
{
    // // Page header
    // function Header()
    // {
    //     // Logo
    //     $this->SetFont('Arial', 'B', 12);
    //     $this->Cell(0, 10, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1, 'C');
    //     $this->SetFont('Arial', '', 9);
    //     $this->Cell(0, 5, 'Divisi: General Affair   Prepayment: Distribusi', 0, 1, 'C');
    //     $this->Ln(5);
    //     $this->SetFont('Arial', 'B', 12);
    //     $this->Cell(0, 10, 'FORM PENGAJUAN PREPAYMENT', 0, 1, 'C');
    //     $this->Ln(5);
    // }

    // // Page footer
    // function Footer()
    // {
    //     // Position at 1.5 cm from bottom
    //     $this->SetY(-15);
    //     // Arial italic 8
    //     $this->SetFont('Arial', 'I', 8);
    //     // Page number
    //     $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    // }
    public function __construct()
    {
        parent::__construct();
    }

    function WriteHTML($html)
    {
        // Parsing HTML string
        $html = str_replace("\n", ' ', $html);
        $html = preg_replace('/<br(\s+)?\/?>/i', "\n", $html);
        $html = strip_tags($html, "<b><i><u><a><img><p><br><ul><li><ol><table><tr><td><th>");

        // Output HTML
        $this->Write(5, $html);
    }
}

// // Instantiation of inherited class
// $pdf = new PDF();
// $pdf->AliasNbPages();
// $pdf->AddPage();

// // Set font for body
// $pdf->SetFont('Arial', '', 10);

// // Content
// $pdf->Cell(30, 10, 'Tanggal:', 0, 0);
// $pdf->Cell(0, 10, '2024-08-30', 0, 1);

// $pdf->Cell(30, 10, 'Nama:', 0, 0);
// $pdf->Cell(0, 10, 'Ikram Syahrudin', 0, 1);

// $pdf->Cell(30, 10, 'Jabatan:', 0, 0);
// $pdf->Cell(0, 10, 'Staff', 0, 1);

// $pdf->Ln(5);
// $pdf->Cell(0, 10, 'Dengan ini bermaksud mengajukan prepayment untuk:', 0, 1);

// $pdf->Cell(30, 10, 'Tujuan:', 0, 0);
// $pdf->MultiCell(0, 10, 'Pengajuan untuk dilakukan distribusi barang ke daerah Bandung', 0, 1);

// $pdf->Ln(5);

// // Table header
// $pdf->SetFont('Arial', 'B', 10);
// $pdf->Cell(60, 10, 'Rincian', 1);
// $pdf->Cell(60, 10, 'Nominal', 1);
// $pdf->Cell(60, 10, 'Keterangan', 1);
// $pdf->Ln();

// // Table content
// $pdf->SetFont('Arial', '', 10);
// $pdf->Cell(60, 10, 'Garpu', 1);
// $pdf->Cell(60, 10, '20.000', 1);
// $pdf->Cell(60, 10, 'Alat makan', 1);
// $pdf->Ln();

// // Total
// $pdf->Cell(120, 10, 'Total:', 1);
// $pdf->Cell(60, 10, '20.000', 1);
// $pdf->Ln(15);

// // Signatures
// $pdf->SetFont('Arial', 'B', 10);
// $pdf->Cell(60, 10, 'Yang melakukan', 1, 0, 'C');
// $pdf->Cell(60, 10, 'Mengetahui', 1, 0, 'C');
// $pdf->Cell(60, 10, 'Menyetujui', 1, 1, 'C');

// $pdf->SetFont('Arial', '', 10);
// $pdf->Cell(60, 10, 'Ikram Syahrudin', 1, 0, 'C');
// $pdf->Cell(60, 10, 'Rahmatullah', 1, 0, 'C');
// $pdf->Cell(60, 10, 'Arya Wijaya', 1, 1, 'C');

// $pdf->Ln(10);

// // Additional notes
// $pdf->SetFont('Arial', 'I', 8);
// $pdf->MultiCell(0, 10, 'Keterangan: *Berikut ini merupakan catatan keterangan prepayment.\n*List ini sudah bagus dan dapat dilakukan segera.', 0, 'L');

// $pdf->Output();
