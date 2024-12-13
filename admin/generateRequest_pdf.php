<?php
ob_start(); // Start output buffering
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
// Include database connection
require 'dbconnect.php';

// Get filtered data from GET parameters
$fromDate = $_GET['from_date'] ?? null;
$toDate = $_GET['to_date'] ?? null;
$requestStatus = $_GET['request_status'] ?? null;

$queryConditions = [];
$queryParams = [];

// Handle date filtering
if ($fromDate && $toDate) {
    $queryConditions[] = "DATE(datetime_request) BETWEEN ? AND ?";
    $queryParams[] = $fromDate;
    $queryParams[] = $toDate;
} elseif ($fromDate) {
    $queryConditions[] = "DATE(datetime_request) >= ?";
    $queryParams[] = $fromDate;
} elseif ($toDate) {
    $queryConditions[] = "DATE(datetime_request) <= ?";
    $queryParams[] = $toDate;
}

// Handle request status filtering
if ($requestStatus) {
    $queryConditions[] = "request_status = ?";
    $queryParams[] = $requestStatus;
}

// Combine conditions into WHERE clause
$whereClause = !empty($queryConditions) ? "WHERE " . implode(" AND ", $queryConditions) : "";

// Fetch filtered requests from the database
$query = "SELECT * FROM royale_request_tbl $whereClause";
$stmt = $conn->prepare($query);
if (!empty($queryParams)) {
    $stmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$stmt->execute();
$requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate total income
$incomeQuery = "SELECT SUM(down_payment + final_payment) AS total_income FROM royale_request_tbl $whereClause";
$incomeStmt = $conn->prepare($incomeQuery);
if (!empty($queryParams)) {
    $incomeStmt->bind_param(str_repeat("s", count($queryParams)), ...$queryParams);
}
$incomeStmt->execute();
$totalIncome = $incomeStmt->get_result()->fetch_assoc()['total_income'] ?? 0;
// Set PDF properties
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document properties
$pdf->SetCreator('Royale Tailoring');
$pdf->SetTitle('Request Report');

// Set margins for the PDF
$pdf->SetMargins(10, 10, 10);  // Left, Top, Right margins (Increased top margin)

// Custom header setup
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

// Add custom header with filtered date and request status
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Date Range: ' . ($fromDate ? $fromDate : 'N/A') . ' to ' . ($toDate ? $toDate : 'N/A'), 0, 1, 'L');
$pdf->Cell(0, 0, 'Request Status: ' . ($requestStatus ? ucfirst($requestStatus) : 'All'), 0, 1, 'L');
$pdf->Ln(5);

// Set table header with custom styles
$pdf->SetFont('helvetica', 'B', 10); // Bold font for table header
$pdf->SetFillColor(200, 220, 255); // Light blue color for table header background
$pdf->Cell(0, 10, 'Request Report', 0, 1, 'C'); // Subheader added here
$pdf->Ln(0);  // Add some space after the subheader

// Table column headers (can be easily edited here)
$pdf->Cell(30, 10, 'Request ID', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Name', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Service', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Type', 1, 0, 'C', 1);
$pdf->Cell(45, 10, 'Fitting Date & Time', 1, 0, 'C', 1);
$pdf->Cell(45, 10, 'Request Date', 1, 0, 'C', 1);
$pdf->Cell(45, 10, 'Payment (Down + Final)', 1, 1, 'C', 1); // Combined payment header

// Populate the table with request data
$pdf->SetFont('helvetica', '', 9); // Regular font for table data
foreach ($requests as $request) {
    // Calculate combined payment (down_payment + final_payment)
    $combinedPayment = $request['down_payment'] + $request['final_payment'];
    
    $pdf->Cell(30, 10, $request['request_id'], 1, 0, 'C');
    $pdf->Cell(40, 10, $request['name'], 1, 0, 'C');
    $pdf->Cell(40, 10, $request['service_name'], 1, 0, 'C');
    $pdf->Cell(30, 10, $request['request_type'], 1, 0, 'C');
    $pdf->Cell(45, 10, $request['fitting_date'] . ' ' . $request['fitting_time'], 1, 0, 'C');
    $pdf->Cell(45, 10, $request['datetime_request'], 1, 0, 'C');
    $pdf->Cell(45, 10, 'PHP ' . number_format($combinedPayment, 2), 1, 1, 'C'); // Display combined payment
}

// Add total income section
$pdf->Ln(0);  // Add some space before income section
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Total Income: PHP ' . number_format($totalIncome, 2), 0, 1, 'R');
$pdf->Ln(5);

// Add "Approved By" section
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Approved By: ______________________', 0, 1, 'L');

// Footer with page number
$pdf->SetY(-15);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 10, 'Page ' . $pdf->getAliasNumPage() . ' of ' . $pdf->getAliasNbPages(), 0, 0, 'C');

ob_end_clean(); // Clean the output buffer and prevent it from being sent to the browser
// Output the PDF to the browser
$pdf->Output('Request_Report.pdf', 'I');
?>
