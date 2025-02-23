$(document).ready(function () {
    let selectedItems = [];

    $(document).on("change", ".select-item", function () {
        let itemId = $(this).data("id");
        let productId = $(this).data("product");
        let quantity = $(this).data("quantity");
        let price = $(this).data("price");
        let productImage = $(this).data("image");
        let orderPrice = price * quantity;

        console.log(`Item selected - ID: ${itemId}, Product: ${productId}, Qty: ${quantity}, Price: ${orderPrice}, Image: ${productImage}`);

        if ($(this).prop("checked")) {
            selectedItems.push({productId, quantity, productImage, orderPrice, price});
        } else {
            selectedItems = selectedItems.filter(item => item.itemId !== itemId);
        }

        console.log("Selected Items:", selectedItems);
        $("#checkoutSelected").prop("disabled", selectedItems.length === 0);
    });

    // Checkout button handler
    $("#checkoutSelected").click(function () {
        if (selectedItems.length === 0) {
            alert("Select at least one item.");
            return;
        }

        console.log("Proceeding to checkout...");
        createPaymentModal();
    });

    // Create payment modal dynamically
    function createPaymentModal() {
        $("#paymentMethodModal").remove(); // Remove any existing modals
        let modalHtml = `
            <div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-labelledby="paymentMethodLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentMethodLabel">Select Payment Method</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="paymentMethodSelect">Choose a Payment Method:</label>
                            <select id="paymentMethodSelect" class="form-control">
                                <option value="COD">Cash on Delivery</option>
                                <option value="Online">Online Payment</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmPaymentButton">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>`;

        $("body").append(modalHtml);
        $("#paymentMethodModal").modal("show");

        $("#confirmPaymentButton").click(function () {
            let paymentMethod = $("#paymentMethodSelect").val();
            console.log("Payment Method Selected:", paymentMethod);
            processOrder(paymentMethod);
        });
    }

    // Process order based on selected payment method
    function processOrder(paymentMethod) {
        let totalAmount = selectedItems.reduce((sum, item) => sum + item.orderPrice, 0);
        console.log("Processing order with payment method:", paymentMethod);
        console.log("Total Amount: ", totalAmount);
        console.log("Selected Items for Order:", selectedItems);

        if (paymentMethod === "COD") {
            $.ajax({
                url: "checkout_selected.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ items: selectedItems, paymentMethod: "COD", totalamount: totalAmount}),
                success: function (response) {
                    console.log("Server response:", response);
                    console.log("Order placed successfully with Cash on Delivery!");
                    sessionStorage.setItem("orderMessage", "Your order was placed successfully!");
                    sessionStorage.setItem("orderMessageType", "success");
                    $("#paymentMethodModal").modal("hide");
                    location.reload();
                },
                error: function (xhr) {
                    console.error("Error processing order:", xhr.responseText);
                    alert("Error processing order.");
                }
            });
        } else if (paymentMethod === "Online") {
            let form = $("<form>", { action: "stripe_checkout.php", method: "POST" })
                .append(
                    $("<input>", { type: "hidden", name: "cartItems", value: JSON.stringify(selectedItems) }),
                    $("<input>", { type: "hidden", name: "totalAmount", value: totalAmount }),
                    $("<input>", { type: "hidden", name: "paymentMethod", value: "Online" })
                );
            console.log("Redirecting to Stripe Payment...");
            $("body").append(form);
            form.submit();
        }
    }
});
