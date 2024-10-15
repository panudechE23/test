$(document).ready(function() {
    const sections = ['parallax', 'newProducts', 'bestSellers', 'address'];
    let currentSection = 0;

    function setupScrollButtons() {
        $('#scrollUp').click(function() {
            if (currentSection > 0) {
                currentSection--;
                scrollToSection(currentSection);
            }
        });

        $('#scrollDown').click(function() {
            if (currentSection < sections.length - 1) {
                currentSection++;
                scrollToSection(currentSection);
            }
        });
    }

    function scrollToSection(index) {
        const targetSection = $('#' + sections[index]);
        if (targetSection.length) {
            $('html, body').animate({
                scrollTop: targetSection.offset().top
            }, 1);
        }
    }

    function setupParallaxEffect() {
        document.addEventListener('scroll', function() {
            var scrollTop = window.pageYOffset;
            document.getElementById('city1').style.transform = 'translateY(' + scrollTop * 0 + 'px)';
            document.getElementById('city2').style.transform = 'translateY(' + scrollTop * 0.3 + 'px)';
        });
    }

    // Initialize functions
    setupScrollButtons();
    setupParallaxEffect();
});
