import './bootstrap';
// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Enable all Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Status indicator animation
    const statusIndicators = document.querySelectorAll('.status-indicator');
    statusIndicators.forEach(indicator => {
        indicator.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
    
    // Platform character limit indicators
    const platformCheckboxes = document.querySelectorAll('input[name="platforms[]"]');
    const contentField = document.getElementById('content');
    
    if (platformCheckboxes && contentField) {
        platformCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCharacterLimit);
        });
        
        function updateCharacterLimit() {
            const limits = [];
            platformCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const platformId = checkbox.value;
                    // Get limit from data attribute or default
                    const limit = checkbox.dataset.charLimit || 2000;
                    limits.push(limit);
                }
            });
            
            const strictestLimit = limits.length ? Math.min(...limits) : 2000;
            const charCount = document.getElementById('charCount');
            
            if (contentField.value.length > strictestLimit) {
                charCount.classList.add('warning');
            } else {
                charCount.classList.remove('warning');
            }
        }
        
        contentField.addEventListener('input', updateCharacterLimit);
    }
});