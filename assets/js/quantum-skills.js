document.addEventListener('DOMContentLoaded', function() {
    function fluctuateSkills() {
        // Get all progress bars and their corresponding value displays
        const progressBars = document.querySelectorAll('.skills .progress-bar');
        const skillValues = document.querySelectorAll('.skills .val');
        
        progressBars.forEach((bar, index) => {
            // Generate random percentage between 10% and 90%
            const randomPercentage = Math.floor(Math.random() * 81) + 10; // 10-90
            
            // Update the progress bar
            bar.style.width = randomPercentage + '%';
            bar.setAttribute('aria-valuenow', randomPercentage);
            
            // Update the displayed percentage text
            if (skillValues[index]) {
                skillValues[index].textContent = randomPercentage + '%';
            }
        });
    }
    
    // Start fluctuation after page loads
    setTimeout(() => {
        fluctuateSkills();
        // Continue fluctuating every 3 seconds
        setInterval(fluctuateSkills, 3000);
    }, 1000);
});

Website/assets/js/quantum-skills.js
document.addEventListener('DOMContentLoaded', function() {
    function fluctuateSkills() {
        // Get all progress bars and their corresponding value displays
        const progressBars = document.querySelectorAll('.skills .progress-bar');
        const skillValues = document.querySelectorAll('.skills .val');
        
        progressBars.forEach((bar, index) => {
            // Generate random percentage between 10% and 90%
            const randomPercentage = Math.floor(Math.random() * 81) + 10; // 10-90
            
            // Update the progress bar with smooth transition
            bar.style.transition = 'width 0.6s ease-in-out';
            bar.style.width = randomPercentage + '%';
            bar.setAttribute('aria-valuenow', randomPercentage);
            
            // Update the displayed percentage text
            if (skillValues[index]) {
                skillValues[index].textContent = randomPercentage + '%';
            }
        });
    }
    
    // Start fluctuation after page loads
    setTimeout(() => {
        fluctuateSkills();
        // Continue fluctuating every 3 seconds
        setInterval(fluctuateSkills, 3000);
    }, 1000);
    
    // Add quantum-themed animation class
    const skillsSection = document.querySelector('.skills');
    if (skillsSection) {
        skillsSection.classList.add('quantum-fluctuation');
    }
});