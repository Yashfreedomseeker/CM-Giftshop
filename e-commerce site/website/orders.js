$(document).ready(function () {
    function loadOrders() {
        $.ajax({
            url: "fetch_orders.php",
            type: "GET",
            success: function (data) {
                $("#adminTable").html(data);
            },
            error: function () {
                alert("Failed to load order data.");
            }
        });
    }

    loadOrders(); // Load orders on page load

    // Handle delete order button
    $(document).on("click", ".delete-order", function () {
        var orderId = $(this).data("id");

        if (confirm("Are you sure you want to delete this order?")) {
            $.ajax({
                url: "delete_order.php",
                type: "POST",
                data: { orderId: orderId },
                success: function (response) {
                    alert(response);
                    loadOrders(); // Reload orders after deletion
                },
                error: function () {
                    alert("Error deleting order.");
                }
            });
        }
    });

    // Handle payment button
    $(document).on("click", ".pay-order", function () {
        var orderId = $(this).data("id");

        if (confirm("Proceed to payment?")) {
            $.ajax({
                url: "process_payment.php",
                type: "POST",
                data: { orderId: orderId },
                success: function (response) {
                    alert(response);
                    loadOrders(); // Refresh order table after payment
                },
                error: function () {
                    alert("Error processing payment.");
                }
            });
        }
    });
});
