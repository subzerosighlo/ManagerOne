<?php
require('inc/fpdf/fpdf.php');
require('inc/functions.php');

// get the ID of the dump ticket to generate a PDF for
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}
$id = $_GET['id'];

// fetch the dump ticket information from the database
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM dump_tickets WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

// Set some styling variables
$primaryColor = array(35, 119, 160);
$secondaryColor = array(211, 211, 211);
$fontColor = array(35, 35, 35);

// Create a new FPDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Add some padding to the top and bottom of the page
$pdf->SetAutoPageBreak(true, 20);

// Output the ticket information as a PDF table
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
$pdf->Cell(0, 20, 'Dump Ticket', 0, 1, 'C');

$pdf->SetFont('Arial', '', 14);
$pdf->SetTextColor($fontColor[0], $fontColor[1], $fontColor[2]);
$pdf->Cell(40, 10, 'Load ID:', 0, 0);
$pdf->Cell(70, 10, $ticket['load_id'], 0, 0);
$pdf->Cell(40, 10, 'Truck Number:', 0, 0);
$pdf->Cell(70, 10, $ticket['truck_number'], 0, 1);

$pdf->Cell(40, 10, 'Gross Weight:', 0, 0);
$pdf->Cell(70, 10, $ticket['gross_weight'], 0, 0);
$pdf->Cell(40, 10, 'Tare Weight:', 0, 0);
$pdf->Cell(70, 10, $ticket['tare_weight'], 0, 1);

$pdf->Cell(40, 10, 'Net Weight (Tons): ', 0, 0);
$pdf->Cell(70, 10, $ticket['net_weight_tons'], 0, 0);
$pdf->Cell(40, 10, 'Company:', 0, 0);
$pdf->Cell(70, 10, $ticket['company'], 0, 1);

$pdf->Cell(40, 10, 'Date:', 0, 0);
$pdf->Cell(70, 10, $ticket['date'], 0, 0);
$pdf->Cell(40, 10, 'Material:', 0, 0);
$pdf->Cell(70, 10, $ticket['material'], 0, 1);

// Add a horizontal line separator
$pdf->SetDrawColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY()+10, 200, $pdf->GetY()+10);

// Output the PDF
$pdf->Output();

?>