document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.simulate-update');

    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const targetRole = this.dataset.role;
            const row = this.closest('tr');
            const roleCell = row.querySelector('td:nth-child(3)');
            const originalText = this.textContent;

            // Désactiver le bouton et indiquer un chargement
            this.textContent = '⏳ Mise à jour...';
            this.disabled = true;
            this.style.opacity = 0.5;

            // Attente simulée de 2 secondes
            setTimeout(() => {
                roleCell.textContent = targetRole;
                this.textContent = originalText;
                this.disabled = false;
                this.style.opacity = 1;
            }, 2000);
        });
    });
});
