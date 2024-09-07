// script.js
const countries = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola',
    'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria',
    // Add all countries here
    'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City',
    'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
];

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Populate the dropdown menu
    countries.forEach(country => {
        const div = document.createElement('div');
        div.textContent = country;
        div.addEventListener('click', () => {
            searchInput.value = country;
            dropdownMenu.style.display = 'none';
        });
        dropdownMenu.appendChild(div);
    });

    // Filter the dropdown menu based on input
    searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();
        const items = dropdownMenu.querySelectorAll('div');
        let hasVisibleItem = false;

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(filter)) {
                item.style.display = 'block';
                hasVisibleItem = true;
            } else {
                item.style.display = 'none';
            }
        });

        dropdownMenu.style.display = hasVisibleItem ? 'block' : 'none';
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!dropdownMenu.contains(event.target) && event.target !== searchInput) {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }
    });
});
