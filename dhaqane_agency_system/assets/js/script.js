document.addEventListener('DOMContentLoaded', function () {
    // Sidebar Toggle for mobile (if added later)

    // Confirm Delete
    const deleteLinks = document.querySelectorAll('.delete-btn');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});
