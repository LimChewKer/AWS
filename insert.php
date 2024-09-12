<<<<<<< HEAD
<?php
$servername = "rds-endpoint";
$username = "admin";
$password = "yourpassword";
$dbname = "inventorydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM items WHERE id=$id");
    echo "<script>alert('Item deleted successfully!'); window.location.href = 'main.html';</script>";
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
        $stmt = $conn->prepare("UPDATE items SET item_name=?, item_quantity=? WHERE id=?");
        $stmt->bind_param("sii", $item_name, $item_quantity, $item_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item saved successfully!'); window.location.href = 'main.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit();
}

// Handle list action
if (isset($_GET['action']) && $_GET['action'] === 'list') {
    $result = $conn->query("SELECT * FROM items");
    
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['item_name']}</td>
                <td>{$row['item_quantity']}</td>
                <td>
                    <button onclick='editItem({$row['id']}, \"{$row['item_name']}\", {$row['item_quantity']})'>Edit</button>
                    <button onclick='confirmDelete({$row['id']})'>Delete</button>
                </td>
              </tr>";
    }
    
    echo "</table>";
    exit();
}

$conn->close();
=======
<?php
$servername = "rds-endpoint";
$username = "admin";
$password = "yourpassword";
$dbname = "inventorydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM items WHERE id=$id");
    echo "<script>alert('Item deleted successfully!'); window.location.href = 'main.html';</script>";
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
        $stmt = $conn->prepare("UPDATE items SET item_name=?, item_quantity=? WHERE id=?");
        $stmt->bind_param("sii", $item_name, $item_quantity, $item_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Item saved successfully!'); window.location.href = 'main.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit();
}

// Handle list action
if (isset($_GET['action']) && $_GET['action'] === 'list') {
    $result = $conn->query("SELECT * FROM items");
    
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['item_name']}</td>
                <td>{$row['item_quantity']}</td>
                <td>
                    <button onclick='editItem({$row['id']}, \"{$row['item_name']}\", {$row['item_quantity']})'>Edit</button>
                    <button onclick='confirmDelete({$row['id']})'>Delete</button>
                </td>
              </tr>";
    }
    
    echo "</table>";
    exit();
}

$conn->close();
>>>>>>> 108a70fc84a00d5fff6f24917ffc735bd1a502d3
?>