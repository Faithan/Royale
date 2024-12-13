<?php
ob_start(); // Start output buffering

require_once('tcpdf/tcpdf.php');  // Ensure the TCPDF path is correct
require 'dbconnect.php';  // Database connection

// Get the filters from the URL
$fromDate = $_GET['from_date'] ?? null;
$toDate = $_GET['to_date'] ?? null;
$orderStatus = $_GET['order_status'] ?? null;

// Prepare query conditions based on the filters
$queryConditions = [];
$queryParams = [];

if ($fromDate && $toDate) {
    $queryConditions[] = "DATE(datetime_order) BETWEEN ? AND ?";
    $queryParams[] = $fromDate;
    $queryParams[] = $toDate;
} elseif ($fromDate) {
    $queryConditions[] = "DATE(datetime_order) >= ?";
    $queryParams[] = $fromDate;
} elseif ($toDate) {
    $queryConditions[] = "DATE(datetime_order) <= ?";
    $queryParams[] = $toDate;
}

if ($orderStatus) {
    $queryConditions[] = "order_status = ?";
    $queryParams[] = $orderStatus;
}

$whereClause = !empty($queryConditions) ? "WHERE " . implode(" AND ", $queryConditions) : "";

// Fetch the order data for the report
$orderQuery = "SELECT `order_id`, `user_name`, `product_name`, `order_variation`, `payment`, `order_status`, `datetime_order` FROM `royale_product_order_tbl` $whereClause";
$stmt = $conn->prepare($orderQuery);
if (!empty($queryParams)) {
    $stmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$stmt->execute();
$orderReports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Initialize TCPDF
$pdf = new TCPDF('L', 'mm', 'A4');  // Set orientation to landscape
$pdf->SetFont('helvetica', '', 12);

// Set document properties
$pdf->SetCreator('Royale Tailoring');
$pdf->SetTitle('Product Order Report');

// Set margins for the PDF
$pdf->SetMargins(10, 10, 10);  // Left, Top, Right margins

// Custom header setup (similar to generateRequest_pdf.php)
$headerTitle = 'ROYALE TAILORING SHOP';
$headerSubTitle = 'Tenazas, Lala, Lanao Del Norte';
$headerContact = '+63 926-201-3081 | info@royaletailoring.com';

// Set header font size for title
$pdf->setHeaderFont(['helvetica', '', 16]); // Larger font size for the header

// Add a page
$pdf->AddPage();

// Print header title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 2, $headerTitle, 0, 1, 'C');

// Print subtitle with custom font size
$pdf->SetFont('helvetica', '', 12);  // Smaller font size for subtitle
$pdf->Cell(0, 0, $headerSubTitle, 0, 1, 'C');

// Print contact info with a further smaller font size
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 0, $headerContact, 0, 1, 'C');

// Add custom header with filtered date and order status
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Date Range: ' . ($fromDate ? $fromDate : 'N/A') . ' to ' . ($toDate ? $toDate : 'N/A'), 0, 1, 'L');
$pdf->Cell(0, 0, 'Order Status: ' . ($orderStatus ? ucfirst($orderStatus) : 'All'), 0, 1, 'L');

// Add subheader for "Order Report"
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Order Report', 0, 1, 'C'); // Subheader added here
$pdf->Ln(0);  // Add some space after the subheader

// Table Header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(200, 220, 255); // Light blue color for table header background

$pdf->Cell(30, 10, 'Order ID', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Customer Name', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Product Name', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Order Variation', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'Payment (PHP)', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Order Status', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Order Date', 1, 1, 'C', 1);

// Table Data
$pdf->SetFont('helvetica', '', 10);
$totalIncome = 0;
foreach ($orderReports as $order) {
    $pdf->Cell(30, 10, $order['order_id'], 1, 0, 'C');
    $pdf->Cell(50, 10, $order['user_name'], 1, 0, 'C');
    $pdf->Cell(50, 10, $order['product_name'], 1, 0, 'C');
    $pdf->Cell(30, 10, $order['order_variation'], 1, 0, 'C');
    $pdf->Cell(35, 10, 'PHP ' . number_format($order['payment'], 2), 1, 0, 'C');
    $pdf->Cell(30, 10, $order['order_status'], 1, 0, 'C');
    $pdf->Cell(50, 10, $order['datetime_order'], 1, 1, 'C');
    $totalIncome += $order['payment'];
}

// Total Income below table
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, "Total Income: PHP" . number_format($totalIncome, 2), 0, 1, 'R');


// Add "Approved By" section
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Approved By: ______________________', 0, 1, 'L');

// Footer with page number
$pdf->SetY(-15);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 10, 'Page ' . $pdf->getAliasNumPage() . ' of ' . $pdf->getAliasNbPages(), 0, 0, 'C');

ob_end_clean(); // Clean the output buffer and prevent it from being sent to the browser
// Output the PDF
$pdf->Output('Order_Report.pdf', 'I');
