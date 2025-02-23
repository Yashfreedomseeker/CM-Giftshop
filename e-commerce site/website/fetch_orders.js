$(document).ready(function () {
    // Load user data into the table
    function loadUsers() {
        $.ajax({
            url: "fetch_orders.php",
            type: "POST",
            success: function (data) {
                $("#orderTable").html(data); // Insert data into tbody, not #users div
            },
            error: function () {
                alert("Failed to load order data.");
            }
        });
    }

    loadUsers(); // Initial load

    // Search functionality
    $("#searchitem").on("keyup", function () {
        var query = $(this).val();
        loadUsers(query); // Fetch filtered data
    });

    function loadUsers(searchQuery = '') {
        $.ajax({
            url: "fetch_orders.php",
            type: "POST",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#orderTable").html(data);
            },
            error: function () {
                alert("Failed to load admin data.");
            }
        });
    }
});