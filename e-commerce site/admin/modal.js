// Function to create the modal dynamically and display messages
function showMessageModal(message, type = "info") {
    // Check if modal exists, if not, create it dynamically
    if (!document.getElementById("messageModal")) {
        let modalHTML = `
            <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="messageModalLabel">Message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modalMessage"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        document.body.insertAdjacentHTML("beforeend", modalHTML);
    }

    // Get modal elements
    var modalMessage = document.getElementById("modalMessage");
    var modalTitle = document.getElementById("messageModalLabel");

    // Update title and message based on type
    if (type === "error") {
        modalTitle.innerHTML = "Error";
        modalMessage.innerHTML = `<div class="alert alert-danger">${message}</div>`;
    } else if (type === "success") {
        modalTitle.innerHTML = "Success";
        modalMessage.innerHTML = `<div class="alert alert-success">${message}</div>`;
    } else {
        modalTitle.innerHTML = "Message";
        modalMessage.innerHTML = `<div class="alert alert-info">${message}</div>`;
    }

    // Show the modal
    var modalElement = document.getElementById("messageModal");
    var modal = new bootstrap.Modal(modalElement);
    modal.show();
}
