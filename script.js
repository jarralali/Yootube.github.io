document.getElementById('menu-icon').addEventListener('click', function() {
    var sidebar = document.getElementById('sidebar');
    var content = document.querySelector('.content');
    if (sidebar.classList.contains('show-sidebar')) {
        sidebar.classList.remove('show-sidebar');
        content.style.marginLeft = '0';
    } else {
        sidebar.classList.add('show-sidebar');
        content.style.marginLeft = '250px';
    }
});

