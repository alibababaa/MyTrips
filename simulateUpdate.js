document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.simulate-update');

    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const targetRole = this.dataset.role;
            const login = this.dataset.login;
            const row = this.closest('tr');
            const roleCell = row.querySelector('td:nth-child(3)');
            const originalText = this.textContent;

            // Icône chargement + désactivation
            this.innerHTML = '⏳';
            this.disabled = true;

            fetch(`admin.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `ajax=1&action=${encodeURIComponent(targetRole)}&login=${encodeURIComponent(login)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    roleCell.textContent = data.newRole;
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur AJAX :', error);
                alert("Une erreur s'est produite.");
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
});
