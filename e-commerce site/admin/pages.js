// sidebar toggling
var el = document.getElementById("wrapper");
var toggleButton = document.getElementById("menu-toggle");

toggleButton.onclick = function(){
    el.classList.toggle("toggled");
}

// Switch between pages
document.querySelectorAll('#sidebar-wrapper a').forEach(link => {
    link.addEventListener('click', function (e) {
        // Skip the "Log Out" link
        if (this.getAttribute('href') === 'login.php') {
            return;
        }

        e.preventDefault();

        const targetId = this.getAttribute('data-target');
        if (!targetId) {
            console.log("No target specified for this link.");
            return;
        }

        // Log the target ID
        console.log("Switching to page:", targetId);

        // Remove 'active' from all pages
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
        });

        // Add 'active' to the target page
        const targetPage = document.getElementById(targetId);
        if (targetPage) {
            targetPage.classList.add('active');
        } else {
            console.log("No page found with ID:", targetId);
        }
    });
});

