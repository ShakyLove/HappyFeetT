<?php
session_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('./img/logo.png', 10, 8, 33);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(55);
        // Título
        $this->Cell(95, 10, 'Listado de Proveedores', 0, 2, 'C');
        $this->Cell(90, 15, 'Happy Feet Technology', 0, 2, 'C');
        $this->Cell(250, 0, $_SESSION['user'], 0, 0, 'C');
        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
require '../bd/conn.php';

$query = mysqli_query($conn, "SELECT * FROM proveedor WHERE estatus = 1 ");
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 10, 'ID', 1, 0, 'C', 0);
$pdf->Cell(30, 10, 'Proveedor', 1, 0, 'C', 0);
$pdf->Cell(50, 10, 'Contacto', 1, 0, 'C', 0);
$pdf->Cell(30, 10, 'Teléfono', 1, 0, 'C', 0);
$pdf->Cell(70, 10, 'Direccion', 1, 1, 'C', 0);
while ($row = mysqli_fetch_array($query)) {

    $pdf->Cell(15, 10, $row['nit_proveedor'], 1, 0, 'C', 0);
    $pdf->Cell(30, 10, $row['proveedor'], 1, 0, 'B', 0);
    $pdf->Cell(50, 10, $row['contacto'], 1, 0, 'B', 0);
    $pdf->Cell(30, 10, $row['telefono'], 1, 0, 'C', 0);
    $pdf->Cell(70, 10, $row['direccion'], 1, 1, 'B', 0);
}

$pdf->Output();
