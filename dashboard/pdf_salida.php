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
        $this->Cell(95, 10, 'Listado de salidas', 0, 2, 'C');
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

$query = mysqli_query($conn, "SELECT s.id_salida, s.fecha_salida, s.estatus, u.usuario, FORMAT(s.total_salida,0) as total_salida
                                                                FROM salida s
                                                                INNER JOIN usuarios u ON s.usuario = u.codigo
                                                                WHERE s.estatus != 10 ORDER BY s.id_salida");
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 10, 'No.', 1, 0, 'C', 0);
$pdf->Cell(50, 10, 'Fecha', 1, 0, 'C', 0);
$pdf->Cell(35, 10, 'Usuario', 1, 0, 'C', 0);
$pdf->Cell(40, 10, 'Total salida', 1, 0, 'C', 0);
$pdf->Cell(40, 10, 'Estado', 1, 1, 'C', 0);

while ($row = mysqli_fetch_array($query)) {

    $formato = 'Y-m-d H:i:s';
    $fecha = DateTime::createFromFormat($formato, $row['fecha_salida']);

    if ($row['estatus'] == 1) {
        $estatus = 'Generada';
    } else {
        $estatus = 'Anulada';
    }

    $pdf->Cell(15, 10, $row['id_salida'], 1, 0, 'C', 0);
    $pdf->Cell(50, 10, $fecha->format('d-m-Y'), 1, 0, 'B', 0);
    $pdf->Cell(35, 10, $row['usuario'], 1, 0, 'C', 0);
    $pdf->Cell(40, 10, $row['total_salida'], 1, 0, 'C', 0);
    $pdf->Cell(40, 10, $estatus, 1, 1, 'C', 0);
}

$pdf->Output();
