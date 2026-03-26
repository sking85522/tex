<?php
session_start();

// Fallback logic in case the library isn't there
if (!file_exists('lib/fpdf/fpdf.php')) {
    die("PDF Library not found. Please contact support.");
}

require('lib/fpdf/fpdf.php');

$clientName = $_GET['client'] ?? 'Valued Client';
$serviceName = $_GET['service'] ?? 'Custom IT Solution';
$price = $_GET['price'] ?? '4000';
$currency = $_GET['currency'] ?? 'INR';
$symbol = ($currency == 'USD') ? '$' : 'Rs. ';

$discount = 0;
if (isset($_GET['discount']) && $_GET['discount'] == '10') {
    $discount = (int)$price * 0.10;
}

$finalPrice = (int)$price - $discount;
$quoteId = "TEX-" . strtoupper(substr(md5(uniqid()), 0, 6));

class PDF extends FPDF {
    // Page header
    function Header() {
        // Logo Simulation (Just text for now to avoid missing image errors)
        $this->SetFont('Arial','B',24);
        $this->SetTextColor(78, 115, 223); // Bootstrap Primary blue
        $this->Cell(0, 10, 'Tech Elevate X', 0, 1, 'L');

        $this->SetFont('Arial','I',10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'AI & IT Service Experts | info@techelevatex.com', 0, 1, 'L');
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0,10,'Thank you for choosing Tech Elevate X! This quote is valid for 15 days.',0,0,'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0,10,'OFFICIAL QUOTATION',0,1,'C');
$pdf->Ln(10);

// Client Details
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 8, 'Prepared For:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0, 8, $clientName, 0, 1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 8, 'Quote Reference:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0, 8, $quoteId, 0, 1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(50, 8, 'Date:', 0, 0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0, 8, date('F j, Y'), 0, 1);

$pdf->Ln(15);

// Table Header
$pdf->SetFillColor(78, 115, 223);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(120, 10, ' Description of Service', 1, 0, 'L', true);
$pdf->Cell(70, 10, ' Amount', 1, 1, 'R', true);

// Table Data
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial','',12);
$pdf->Cell(120, 15, ' ' . $serviceName, 1, 0, 'L');
$pdf->Cell(70, 15, ' ' . $symbol . number_format($price), 1, 1, 'R');

if ($discount > 0) {
    $pdf->Cell(120, 15, ' AI Promotional Discount (10%)', 1, 0, 'L');
    $pdf->SetTextColor(220, 53, 69); // Red for discount
    $pdf->Cell(70, 15, ' - ' . $symbol . number_format($discount), 1, 1, 'R');
}

// Total
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(120, 15, ' TOTAL ESTIMATE', 1, 0, 'R');
$pdf->SetTextColor(28, 200, 138); // Green for total
$pdf->Cell(70, 15, ' ' . $symbol . number_format($finalPrice), 1, 1, 'R');

$pdf->Ln(20);
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('Arial','I',11);
$pdf->MultiCell(0, 8, "To accept this quotation and initiate the project, please reply to this document or click the 'Start Project' button on our website. \n\nWe look forward to elevating your business with Next-Gen technology.");

// Output
$pdf->Output('I', "Quotation_$quoteId.pdf");
?>
