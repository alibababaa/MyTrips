document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.getElementById('sort-select');

    if (!sortSelect) return;

    sortSelect.addEventListener('change', function () {
        const sortBy = this.value;
        const cardsContainer = document.querySelector('.destinations');
        const cards = Array.from(cardsContainer.getElementsByClassName('trip-card'));

        cards.sort((a, b) => {
            let aVal = a.dataset[sortBy];
            let bVal = b.dataset[sortBy];

            if (sortBy === 'date') {
                return new Date(aVal) - new Date(bVal);
            } else {
                return Number(aVal) - Number(bVal);
            }
        });

        cards.forEach(card => cardsContainer.appendChild(card));
    });
});
