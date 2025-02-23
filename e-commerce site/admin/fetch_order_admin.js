$(document).ready(function () {
    // Load user data into the table
    function loadUsers() {
        $.ajax({
            url: "fetch_order_admin.php",
            type: "POST",
            success: function (data) {
                $("#orderviewTable").html(data); // Insert data into tbody, not #users div
            },
            error: function () {
                console.log("Failed to load order data.");
            }
        });
    }

    loadUsers(); // Initial load

    // Search functionality
    $("#searchOrder").on("keyup", function () {
        var query = $(this).val();
        loadUsers(query); // Fetch filtered data
    });

    function loadUsers(searchQuery = '') {
        $.ajax({
            url: "fetch_order_admin.php",
            type: "POST",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#orderviewTable").html(data);
            },
            error: function () {
                console.log("Failed to load order data.");
            }
        });
    }
});