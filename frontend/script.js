// Toggle submenu
const menuItems = document.querySelectorAll('.has-submenu');

menuItems.forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        this.classList.toggle('active');
        const submenu = this.querySelector('.submenu');
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    });
});

// Close submenu when clicking outside
document.addEventListener('click', function(e) {
    const target = e.target;
    const isSubmenuOpen = document.querySelector('.has-submenu.active');

    if (isSubmenuOpen && !target.closest('.has-submenu')) {
        isSubmenuOpen.classList.remove('active');
        const submenu = isSubmenuOpen.querySelector('.submenu');
        submenu.style.display = 'none';
    }
});

// Confirm social media icon click
const socialIcons = document.querySelectorAll('.social-icon');

socialIcons.forEach(icon => {
    icon.addEventListener('click', function(e) {
        e.preventDefault();
        const domain = this.getAttribute('data-domain');
        const confirmed = confirm(`You are about to navigate to ${domain}. Do you want to proceed?`);

        if (confirmed) {
            window.open(`https://${domain}`, '_blank');
        }
    });
});