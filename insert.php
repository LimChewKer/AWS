<?php
$servername = "proj-db.ccr6d1e6bnfy.us-east-1.rds.amazonaws.com"; // RDS endpoint
$username = "admin"; // RDS username
$password = "coolstrongpassword"; // RDS password
$dbname = "inventorydb"; // Database name

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href = 'index.html';</script>";
    } else {
        echo "Error deleting item: " . $stmt->error;
    }
    $stmt->close();
    exit();
}

// Handle insert/update action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $item_quantity = $_POST['item_quantity'];
    $item_id = $_POST['item_id'];

    if (empty($item_id)) {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO items (item_name, item_quantity) VALUES (?, ?)");
        $stmt->bind_param("si", $item_name, $item_quantity);
    } else {
        // Update existing item
        $stmt = $conn->prepare("UPDATE items SET item_name = ?, item_quantity = ? WHERE id = ?");
        $stmt->bind_param("sii", $item_name, $item_quantity, $item_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item saved successfully!'); window.location.href = 'index.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit();
}

// Handle list action (to display items)
if (isset($_GET['action']) && $_GET['action'] === 'list') {
    $result = $conn->query("SELECT * FROM items");

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['item_name']}</td>
                    <td>{$row['item_quantity']}</td>
                    <td>
                        <button onclick='editItem({$row['id']}, \"{$row['item_name']}\", {$row['item_quantity']})'>Edit</button>
                        <button class='delete' onclick='confirmDelete({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No items found.";
    }
    exit();
}

$conn->close();
?>
