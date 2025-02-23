$(document).ready(function () {
    // Load user data into the table
    function loadUsers() {
        $.ajax({
            url: "fetch_customers.php",
            type: "GET",
            success: function (data) {
                $("#userTable").html(data); // Insert data into tbody, not #users div
            },
            error: function () {
                alert("Failed to load user data.");
            }
        });
    }

    loadUsers(); // Initial load

    $("#searchcus").on("keyup", function () {
        var query = $(this).val();
        loadUsers(query); // Fetch filtered data
    });

    function loadUsers(searchQuery = '') {
        $.ajax({
            url: "fetch_customers.php",
            type: "GET",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#userTable").html(data);
            },
            error: function () {
                alert("Failed to load admin data.");
            }
        });
    }

    // Handle delete button click
    $(document).on("click", ".delete-user", function () {
        var uid = $(this).data("id");

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: "delete_user.php",
                type: "POST",
                data: { uid: uid },
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
