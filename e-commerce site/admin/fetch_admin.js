$(document).ready(function () {
    // Load user data into the table
    function loadUsers() {
        $.ajax({
            url: "fetch_admin.php",
            type: "GET",
            success: function (data) {
                $("#adminTable").html(data); // Insert data into tbody, not #users div
            },
            error: function () {
                alert("Failed to load admin data.");
            }
        });
    }

    loadUsers(); // Initial load

    // Search functionality
    $("#searchInput").on("keyup", function () {
        var query = $(this).val();
        loadUsers(query); // Fetch filtered data
    });

    function loadUsers(searchQuery = '') {
        $.ajax({
            url: "fetch_admin.php",
            type: "GET",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#adminTable").html(data);
            },
            error: function () {
                alert("Failed to load admin data.");
            }
        });
    }

    // Handle delete button click
    $(document).on("click", ".delete-admin", function () {
        var adminid = $(this).data("id");

        if (confirm("Are you sure you want to delete this admin user?")) {
            $.ajax({
                url: "delete_admin.php",
                type: "POST",
                data: { adminId: adminid },
                success: function (response) {
                    alert(response);
                    loadUsers(); // Reload the user list after deletion
                },
                error: function () {
                    alert("Error deleting user.");
                }
            });
        }
    });
});
