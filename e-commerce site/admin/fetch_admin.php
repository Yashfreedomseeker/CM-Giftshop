<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT adminId, adminName, adminMobile FROM admin 
            WHERE adminName LIKE '%$search%' 
            OR adminMobile LIKE '%$search%' 
            OR adminId LIKE '%$search%'";
            
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['adminId']}</td>
                    <td>{$row['adminName']}</td>
                    <td>{$row['adminMobile']}</td>
                    <td>
                        <button class='btn btn-danger delete-admin' data-id='{$row['adminId']}'>
                            <i class='fas fa-trash-alt'></i> Delete
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No admin users found.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
}

$conn->close();
?>
