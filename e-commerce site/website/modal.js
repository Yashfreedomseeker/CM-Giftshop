function showMessageModal(message, type = "info") {
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
        document.body.insertAdjacentHTML("beforeend", modalHTML);
    }

    let modalMessage = document.getElementById("modalMessage");
    let modalTitle = document.getElementById("messageModalLabel");

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

    let modalElement = document.getElementById("messageModal");
    let modal = new bootstrap.Modal(modalElement);
    modal.show();
}
