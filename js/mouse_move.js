document.addEventListener('mousemove', function(e) {
    const circle = document.getElementById('circle');
    
    // Check if circle is found
    if (circle) {
        // Get the mouse position
        const mouseX = e.clientX;
        const mouseY = e.clientY;
        const isIndexPage = window.location.pathname.endsWith('index.php');
        
        // Adjust the x position by -40px only if on index.php
        const xOffset = isIndexPage ? -90 : 0;
        // Move the circle to the mouse position
        circle.style.transform = `translate(${mouseX - 15}px, ${mouseY - 15}px)`; // Adjust the offset for centering
    }
});
