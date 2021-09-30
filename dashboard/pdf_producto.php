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
        $this->Cell(95, 10, 'Listado de Productos', 0, 2, 'C');
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

$query = mysqli_query($conn, "SELECT p.codigo_prod, p.descripcion, FORMAT(p.precio,0) as precio, p.existencia, pr.proveedor, p.foto, c.descripcion as category
                                    FROM ((productos p 
                                    INNER JOIN proveedor pr ON p.proveedor = pr.nit_proveedor)
                                    INNER JOIN categorias c ON p.category = c.categoria_id)
                                    WHERE p.estatus = 1 ORDER BY pr.proveedor");
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 10, 'Codigo', 1, 0, 'C', 0);
$pdf->Cell(40, 10, 'Descripcion', 1, 0, 'C', 0);
$pdf->Cell(25, 10, 'Cantidad', 1, 0, 'C', 0);
$pdf->Cell(30, 10, 'Precio', 1, 0, 'C', 0);
$pdf->Cell(40, 10, 'Proveedor', 1, 0, 'C', 0);
$pdf->Cell(40, 10, 'Categoría', 1, 1, 'C', 0);
while ($row = mysqli_fetch_array($query)) {

    $pdf->Cell(15, 10, $row['codigo_prod'], 1, 0, 'C', 0);
    $pdf->Cell(40, 10, $row['descripcion'], 1, 0, 'B', 0);
    $pdf->Cell(25, 10, $row['existencia'], 1, 0, 'C', 0);
    $pdf->Cell(30, 10, $row['precio'], 1, 0, 'C', 0);
    $pdf->Cell(40, 10, $row['proveedor'], 1, 0, 'C', 0);
    $pdf->Cell(40, 10, $row['category'], 1, 1, 'C', 0);
}

$pdf->Output();
