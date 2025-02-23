$(document).ready(function () {
    // Load product data into the table
    function loadProducts() {
        $.ajax({
            url: "fetch_product.php",
            type: "GET",
            success: function (data) {
                $("#productTable").html(data);
            },
            error: function () {
                alert("Failed to load product data.");
            }
        });
    }

    loadProducts(); // Initial load

    $("#searchProduct").on("keyup", function () {
        var query = $(this).val();
        loadProducts(query); // Fetch filtered data
    });

    function loadProducts(searchQuery = '') {
        $.ajax({
            url: "fetch_product.php",
            type: "GET",
            data: { search: searchQuery }, // Passing `search` parameter
            success: function (data) {
                $("#productTable").html(data);
            },
            error: function () {
                alert("Failed to load Product data.");
            }
        });
    }

    // Handle delete button click
    $(document).on("click", ".delete-product", function () {
        var productId = $(this).data("id");

        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: "delete_product.php",
                type: "POST",
                data: { productId: productId },
                success: function (response) {
                    alert(response);
                    loadProducts(); // Reload the product list after deletion
                },
                error: function () {
                    alert("Error deleting product.");
                }
            });
        }
    });
});
