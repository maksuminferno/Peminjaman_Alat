// Borrower Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Simple hover effect for quick actions
    const hoverElements = [
        { selector: '.hover-bg-primary', bgColor: '#007bff', textColor: 'white' },
        { selector: '.hover-bg-success', bgColor: '#28a745', textColor: 'white' },
        { selector: '.hover-bg-info', bgColor: '#17a2b8', textColor: 'white' },
        { selector: '.hover-bg-warning', bgColor: '#ffc107', textColor: 'black' }
    ];

    hoverElements.forEach(element => {
        const elements = document.querySelectorAll(element.selector);
        elements.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = element.bgColor;
                this.style.color = element.textColor;
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.color = element.textColor;
                }
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.color = '';
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.color = '';
                }
            });
        });
    });
});