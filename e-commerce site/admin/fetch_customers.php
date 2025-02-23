<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT uid, name, address, phone, email FROM users
            WHERE name LIKE '%$search%' 
            OR address LIKE '%$search%' 
            OR uid LIKE '%$search%' 
            OR phone LIKE '%$search%' 
            OR email LIKE '%$search%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['uid']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['email']}</td>
                    <td>
                        <button class='btn btn-danger delete-user' data-id='{$row['uid']}'>
                            <i class='fas fa-trash-alt'></i> Delete
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No users found.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
}

$conn->close();
?>
