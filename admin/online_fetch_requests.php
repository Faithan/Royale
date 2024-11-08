<?php
require 'dbconnect.php';

// Get the search query, request status, and gender from the AJAX request
$search_query = isset($_GET['search_query']) ? $conn->real_escape_string($_GET['search_query']) : '';
$request_status = isset($_GET['request_status']) ? $conn->real_escape_string($_GET['request_status']) : '';
$gender = isset($_GET['gender']) ? $conn->real_escape_string($_GET['gender']) : '';

// Build query to fetch filtered requests
$query_requests = "SELECT *
                   FROM royale_request_tbl 
                   WHERE request_type = 'online'"; // Filter by request_type 'online'

// Filter by request_status if a specific status is selected and not "all"
if ($request_status && $request_status !== 'all') {
    $query_requests .= " AND request_status = '$request_status'";
}

// Filter by gender if a specific gender is selected and not "all"
if ($gender && $gender !== 'all') {
    $query_requests .= " AND gender = '$gender'";
}

// Filter by search query if provided
if ($search_query) {
    $query_requests .= " AND (request_id LIKE '%$search_query%' 
                          OR request_status LIKE '%$search_query%' 
                            OR pattern_status LIKE '%$search_query%'
                          OR work_status LIKE '%$search_query%'
                          OR name LIKE '%$search_query%' 
                          OR service_name LIKE '%$search_query%' 
                          OR address LIKE '%$search_query%' 
                          OR special_group LIKE '%$search_query%' 
                          OR fitting_date LIKE '%$search_query%')";
}

// Order the results by request_id in descending order
$query_requests .= " ORDER BY request_id DESC";

$result_requests = $conn->query($query_requests);

if ($result_requests->num_rows > 0) {
    while ($row_request = $result_requests->fetch_assoc()) {
        // Start the table row and make it clickable using a hyperlink
        echo "<tr onclick=\"window.location='online_view_request.php?request_id=" . $row_request['request_id'] . "'\">";
        echo "<td>" . $row_request['request_id'] . "</td>";
        // For request_status
        $request_status_color = match (strtolower($row_request['request_status'])) {
            'pending' => 'gray',
            'cancelled' => 'red',
            'accepted', 'ongoing' => 'blue',
            'completed' => 'green',
            default => 'black'
        };
        echo "<td style='color: $request_status_color; font-weight: bold;'>" . ucfirst($row_request['request_status']) . "</td>";

        // For pattern_status
        $pattern_status_color = match (strtolower($row_request['pattern_status'])) {
            'pending' => 'gray',
            'cancelled' => 'red',
            'accepted', 'ongoing' => 'blue',
            'completed' => 'green',
            default => 'black'
        };
        echo "<td style='color: $pattern_status_color; font-weight: bold;'>" . ucfirst($row_request['pattern_status']) . "</td>";

        // For work_status
        $work_status_color = match (strtolower($row_request['work_status'])) {
            'pending' => 'gray',
            'cancelled' => 'red',
            'accepted', 'ongoing' => 'blue',
            'completed' => 'green',
            default => 'black'
        };
        echo "<td style='color: $work_status_color; font-weight: bold;'>" . ucfirst($row_request['work_status']) . "</td>";

        echo "<td>" . $row_request['name'] . "</td>";
        echo "<td>" . $row_request['service_name'] . "</td>";
        echo "<td>" . ucfirst($row_request['gender']) . "</td>";
        echo "<td>" . $row_request['address'] . "</td>";
        echo "<td>" . $row_request['special_group'] . "</td>";
        echo "<td>" . $row_request['fitting_date'] . "</td>";

        // Display multiple photos if they are comma-separated
        $photos = explode(',', $row_request['photo']);
        echo "<td>";
        foreach ($photos as $photo) {
            echo "<img src='../uploads/$photo' alt='Photo' >";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No records found.</td></tr>";
}
