<?php
session_start();
include'db.php';

// Retrieve the order_id from the query string
$order_id = $_GET['order_id'];

// Retrieve the transaction_receipt from the database
$result = mysqli_query($conn, "SELECT transaction_receipt FROM orders WHERE order_id = '$order_id'");
$row = mysqli_fetch_assoc($result);
$receipt_data = $row['transaction_receipt'];

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
	<title>View Receipt</title>
        <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }
            setTimeout("preventBack()", 0);

            window.onunload = function () {
                null;
            };

            // Regenerate session ID on each page load
            window.onload = function () {
                var xhttp = new XMLHttpRequest();
                xhttp.open("GET", "refresh_session.php", true);
                xhttp.send();
            };
            // Disable refresh
            document.onkeydown = function (e) {
                if (e.keyCode == 116) {
                    return false;
                }
            };

            document.onmousedown = function (e) {
                if (e.button == 4) {
                    return false;
                }
            };
        </script>
</head>
<body>
	<img src="data:image/png;base64,<?php echo base64_encode($receipt_data); ?>" alt="Transaction Receipt">
</body>
</html>


