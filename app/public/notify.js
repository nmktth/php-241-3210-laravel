document.addEventListener('DOMContentLoaded', function() {
    var dropdownElements = document.querySelectorAll('.nav-item.dropdown');
    
    dropdownElements.forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            var dropdown = this.querySelector('.dropdown-toggle');
            var dropdownMenu = this.querySelector('.dropdown-menu');
            
            if (dropdown && dropdownMenu) {
                dropdown.classList.add('show');
                dropdownMenu.classList.add('show');
            }
        });
        
        element.addEventListener('mouseleave', function() {
            var dropdown = this.querySelector('.dropdown-toggle');
            var dropdownMenu = this.querySelector('.dropdown-menu');
            
            if (dropdown && dropdownMenu) {
                dropdown.classList.remove('show');
                dropdownMenu.classList.remove('show');
            }
        });
    });
});