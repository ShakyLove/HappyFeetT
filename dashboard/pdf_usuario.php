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
        $this->Cell(95, 10, 'Listado de Usuarios', 0, 2, 'C');
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

$query = mysqli_query($conn, "SELECT u.codigo, u.nombre, u.correo, u.usuario, r.rol FROM usuarios u INNER JOIN rol r ON u.rol = r.id_rol WHERE estatus = 1");

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 10, 'ID', 1, 0, 'C', 0);
$pdf->Cell(50, 10, 'Nombre', 1, 0, 'C', 0);
$pdf->Cell(70, 10, 'Correo', 1, 0, 'C', 0);
$pdf->Cell(30, 10, 'Nombre Usuario', 1, 0, 'C', 0);
$pdf->Cell(30, 10, 'Tipo Usuario', 1, 1, 'C', 0);
while ($row = mysqli_fetch_array($query)) {

    $pdf->Cell(15, 10, $row['codigo'], 1, 0, 'C', 0);
    $pdf->Cell(50, 10, $row['nombre'], 1, 0, 'B', 0);
    $pdf->Cell(70, 10, $row['correo'], 1, 0, 'B', 0);
    $pdf->Cell(30, 10, $row['usuario'], 1, 0, 'C', 0);
    $pdf->Cell(30, 10, $row['rol'], 1, 1, 'C', 0);
}

$pdf->Output();
