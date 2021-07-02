<?php
session_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('./img/logo.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(55);
    // Título
    $this->Cell(95,10,'Listado de Entradas',0,2,'C');
    $this->Cell(90,15,'Happy Feet Technology',0,2,'C');
    $this->Cell(250,0,$_SESSION['user'],0,0,'C');
    // Salto de línea
    $this->Ln(10);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
require'../bd/conn.php';

$query = mysqli_query($conn, "SELECT e.id_entrada, p.descripcion, e.fecha_entrada, e.cantidad, e.precio_entrada, u.usuario  
                                    FROM ((entrada e
                                    INNER JOIN productos p ON e.cod_producto = p.codigo_prod)
                                    INNER JOIN usuarios u ON e.usuario_id = u.codigo)
                                    ORDER BY e.id_entrada ASC");
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,10, 'ID', 1, 0, 'C', 0);
$pdf->Cell(40,10, 'Producto', 1, 0, 'C', 0);
$pdf->Cell(30,10, 'Cantidad', 1, 0, 'C', 0);
$pdf->Cell(30,10, 'Precio Unidad', 1, 0, 'C', 0);
$pdf->Cell(40,10, 'Fecha', 1, 0, 'C', 0);
$pdf->Cell(40,10, 'Precio Total', 1, 1, 'C', 0);
while($row = mysqli_fetch_array($query)){

    $formato = 'Y-m-d H:i:s';
    $fecha = DateTime::createFromFormat($formato, $row['fecha_entrada']);

    $pdf->Cell(15,10, $row['id_entrada'], 1, 0, 'C', 0);
    $pdf->Cell(40,10, $row['descripcion'], 1, 0, 'B', 0);
    $pdf->Cell(30,10, $row['cantidad'], 1, 0, 'C', 0);
    $pdf->Cell(30,10, $row['precio_entrada'], 1, 0, 'C', 0);
    $pdf->Cell(40,10, $fecha->format('d-m-Y'), 1, 0, 'C', 0);
    $pdf->Cell(40,10, $row['cantidad'] * $row['precio_entrada'], 1, 1, 'C', 0);
}

$pdf->Output();
?>