$(document).ready(function () {
    // Load product data into the table
    function loadCart() {
        $.ajax({
            url: "fetch_cart_items.php",
            type: "GET",
            success: function (data) {
                $("#cartTable").html(data);
            },
            error: function () {
                alert("Failed to load product data.");
            }
        });
    }

    loadCart(); // Initial load

    $("#searchitem").on("keyup", function () {
        var query = $(this).val();
        loadCart(query); // Fetch filtered data
    });

    function loadCart(searchQuery = '') {
        $.ajax({
            url: "fetch_cart_items.php",
            type: "GET",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#cartTable").html(data);
            },
            error: function () {
                alert("Failed to load Product data.");
            }
        });
    }

    // Handle delete button click
    $(document).on("click", ".delete-item", function () {
        var itemid = $(this).data("id");

        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: "delete_cart_items.php",
                type: "POST",
                data: { itemId: itemid },
                success: function (response) {
                    alert(response);
                    loadCart(); // Reload the product list after deletion
                },
                error: function () {
                    alert("Error deleting product.");
                }
            });
        }
    });
});
