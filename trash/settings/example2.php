<?php
include ('dbconnect.php'); // Tsekaha kung ang pag-import sa dbconnect.php file sakto

// Initialize ang array nga maghulagway sa mga options sa select field
$options = array();

// Query sa database aron makuha ang tanan nga balor sa `service_name` column
$sql = "SELECT DISTINCT service_name FROM services";
$result = $con->query($sql);

// Check kung adunay resulta sa query
if ($result->num_rows > 0) {
    // Loop sa mga resulta ug ipuno ang array sa mga options
    while ($row = $result->fetch_assoc()) {
        $options[] = $row["service_name"];
    }
}

// I-display ang select field nga may mga options
echo "<select name='req-type' id='' onchange='changeColorSelect(this)'>";
echo "<option disabled selected value=''>Type of Request</option>"; // Default nga option

// I-check kung adunay mga options gikan sa database
if (!empty($options)) {
    // Loop sa mga options gikan sa database aron i-display sa select field
    foreach ($options as $option) {
        echo "<option value='" . $option . "'>" . $option . "</option>";
    }
} else {
    // Kung walay resulta gikan sa database, i-maintain ang existing nga options
    echo "<option value='For Repair'>For Clothing Repair</option>";
    echo "<option value='For Making'>For Cloth Making</option>";
    echo "<option value='For Renting'>For Cloth Renting</option>";
    echo "<option value='For Purchasing'>For Cloth Buying</option>";
}

echo "</select>";
?>