$(document).ready(function () {
    function loadProducts() {
        console.log("Starting AJAX request to fetch products...");

        $.ajax({
            url: "display_cards.php",
            type: "GET",
            dataType: "json",
            success: function (products) {
                console.log("AJAX request successful. Received data:", products);

                if (products.error) {
                    console.error("Error from server:", products.error);
                    return;
                }

                let categorySections = {};
                let modals = "";
                let firstItem = {};

                const categoryMapping = {
                    "FS": "cit-f",
                    "SC": "cit-s",
                    "HL": "cit-hd",
                    "FA": "cit-ca",
                    "KG": "cit-k"
                };

                products.forEach(product => {
                    let category = product.catId;
                    let categoryClass = categoryMapping[category] || "";
                    let isActive = firstItem[category] ? "" : "active";
                    if (!firstItem[category]) firstItem[category] = true;

                    let basepath = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/') + '/';

                    let cardHTML = `
                        <div class="carousel-item cit ${isActive} ${categoryClass}">
                            <div class="card">
                                <img src="${basepath}${product.image}" class="card-img-top" alt="${product.productName}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.productName}</h5>
                                    <p class="card-text">Rs. ${product.price}/=</p>
                                    <a href="#item${product.productId}" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal${product.productId}">View Item</a>
                                </div>
                            </div>
                        </div>
                    `;

                    if (!categorySections[category]) {
                        categorySections[category] = "";
                    }
                    categorySections[category] += cardHTML;

                    modals += `
                        <div class="modal fade" id="modal${product.productId}" tabindex="-1" aria-labelledby="modalLabel${product.productId}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="modalLabel${product.productId}">${product.productName}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h3>Rs. ${product.price}/=</h3>
                                        <p>${product.description}</p>
                                        <p>Availability: ${product.stock} in stock</p>
                                        <div class="quantity-container">
                                            <input type="number" class="quantity-input" id="quantity-${product.productId}" value="1" min="1" max="${product.stock}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success buy-item" 
                                            data-id="${product.productId}" 
                                            data-name="${product.productName}" 
                                            data-price="${product.price}" 
                                            data-image="${basepath}${product.image}">Buy</button>
                                        <button type="button" class="btn btn-warning add-to-cart" data-product-id="${product.productId}" data-product-image="${basepath}${product.image}">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                for (let category in categorySections) {
                    if ($(`#${category}`).length) {
                        $(`#${category}`).html(categorySections[category]);
                        console.log(`Products inserted into the #${category} section.`);
                    } else {
                        console.warn(`No section found for category ${category}.`);
                    }
                }

                $("body").append(modals);
                console.log("All modals added successfully.");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed:", textStatus, errorThrown);
            }
        });
    }

    loadProducts();

    $(document).on("click", ".add-to-cart", function () {
        let productId = $(this).data("product-id");
        let productImage = $(this).data("product-image");
        let quantity = $(`#quantity-${productId}`).val();

        console.log("Adding to cart. Product ID: " + productId + ", Quantity: " + quantity + ", Image: " + productImage);

        $.ajax({
            url: "add_cart.php",
            type: "POST",
            data: {
                productId: productId,
                quantity: quantity,
                image: productImage
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Item added to cart successfully!");
                } else {
                    alert("Failed to add item to cart: " + response.message);
                }
            },
            error: function () {
                alert("Error adding item to cart.");
            }
        });
    });

    // Inject confirmation modal into the body
    $("body").append(`
        <div class="modal fade" id="confirmBuyModal" tabindex="-1" aria-labelledby="confirmBuyLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmBuyLabel">Confirm Your Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="confirmProductImage" src="" alt="Product Image" width="300px" height="300px">
                        <h4 id="confirmProductName"></h4>
                        <p id="confirmProductPrice"></p>
                        <label for="confirmQuantity">Quantity:</label>
                        <input type="number" id="confirmQuantity" class="form-control" value="1" min="1">
                        <h4 id="confirmTotal"></h4>
                        <label for="confirmPaymentMethod">Select Payment Method:</label>
                        <select id="confirmPaymentMethod" class="form-control">
                            <option value="COD">Cash on Delivery</option>
                            <option value="Online">Online Payment</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="confirmProductId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmOrderButton">Confirm Order</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    // Show confirmation modal and close product modal when Buy button is clicked
    $(document).on("click", ".buy-item", function () {
        let productId = $(this).data("id");
        let productName = $(this).data("name");
        let productPrice = parseFloat($(this).data("price"));
        let productImage = $(this).data("image");
        let paymentMethod = $("#confirmPaymentMethod").val();
        let productModal = `#modal${productId}`; // Target the correct modal

        // Fetch the updated quantity from the input field inside the modal
        let quantity = parseInt($(`#quantity-${productId}`).val()) || 1; 
        let totalPrice = productPrice * quantity; // Calculate the total price
        console.log("Buying product. Product ID: " + productId + ", Quantity: " + quantity + ", Total Price: " + totalPrice);

        // Move focus out of the modal before hiding it
        $(productModal).find(".btn-close").trigger("focus"); 
        $(productModal).modal("hide");

        setTimeout(() => {
            $("#confirmProductId").val(productId);
            $("#confirmProductName").text(productName);
            $("#confirmProductImage").attr("src", productImage);
            $("#confirmProductPrice").text(`Rs. ${productPrice}`);
            $("#confirmQuantity").val(quantity);
            $("#confirmTotal").text(`Total: Rs. ${totalPrice}`);
            $("#confirmPaymentMethod").val(paymentMethod);
            $("#confirmBuyModal").modal("show"); // Show confirmation modal
        }, 300); // Small delay to ensure smooth transition
    });

    // Update total price when quantity changes
    $(document).on("input", "#confirmQuantity", function () {
        let quantity = parseInt($(this).val()) || 1;
        let unitPrice = parseFloat($("#confirmProductPrice").text().replace("Rs. ", ""));
        let total = unitPrice * quantity;
        $("#confirmTotal").text(`Total: Rs. ${total}`);

        console.log("Updated total price: Rs. " + total);
    });

    // Place order on confirmation
    $("#confirmOrderButton").click(function () {
        let productId = $("#confirmProductId").val();
        let quantity = parseInt($("#confirmQuantity").val()) || 1;
        let unitPrice = parseFloat($("#confirmProductPrice").text().replace("Rs. ", ""));
        let orderPrice = unitPrice * quantity;
        let productImage = $("#confirmProductImage").attr("src");
        let paymentMethod = $("#confirmPaymentMethod").val();

        console.log("Confirming order. Product ID: " + productId + ", Quantity: " + quantity + ", Total Price: Rs. " + orderPrice + ", payment: " + paymentMethod);

        if (paymentMethod === "COD") {
            $.ajax({
                url: "placed_orders.php",
                type: "POST",
                data: {
                    productId: productId,
                    quantity: quantity,
                    orderPrice: orderPrice,
                    productImage: productImage,
                    paymentMethod: paymentMethod
                },
                dataType: "json",
                success: function (response) {
                    console.log("Response from place_orders.php:", response);
                    if (response.success) {
                        console.log("Order placed successfully!");
                        sessionStorage.setItem("orderMessage", "Your order was placed successfully!");
                        sessionStorage.setItem("orderMessageType", "success");
                        $("#confirmBuyModal").modal("hide");
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX error:", textStatus, errorThrown, jqXHR); // More detailed error logging
                    sessionStorage.setItem("orderMessage", "There was an error processing your order.");
                    sessionStorage.setItem("orderMessageType", "error");

                }
            });
        } else if (paymentMethod === "Online") {
            // Create and submit a hidden form for POST request
            let form = $("<form>", {
                action: "payment.php",
                method: "POST"
            }).append(
                $("<input>", { type: "hidden", name: "productId", value: productId }),
                $("<input>", { type: "hidden", name: "quantity", value: quantity }),
                $("<input>", { type: "hidden", name: "orderPrice", value: orderPrice }),
                $("<input>", { type: "hidden", name: "productImage", value: productImage }),
                $("<input>", { type: "hidden", name: "paymentMethod", value: paymentMethod }),
                $("<input>", { type: "hidden", name: "price", value: unitPrice })
            );
            console.log(productId,quantity,orderPrice,paymentMethod, unitPrice);
            $("body").append(form);
            form.submit();
        }
    });    
});
