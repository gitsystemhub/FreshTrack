document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Form validation
    $('form').on('submit', function(e) {
        let valid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            $('#form-error').removeClass('d-none');
        }
    });
    
    // Real-time freshness calculation
    if (document.getElementById('harvest_date') && document.getElementById('shelf_life')) {
        const harvestDate = document.getElementById('harvest_date');
        const shelfLife = document.getElementById('shelf_life');
        
        function calculateFreshness() {
            if (harvestDate.value && shelfLife.value) {
                const harvest = new Date(harvestDate.value);
                const today = new Date();
                const diffTime = Math.abs(today - harvest);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                const freshness = Math.max(0, 100 - (diffDays / shelfLife.value) * 100);
                
                console.log(`Days since harvest: ${diffDays}, Freshness: ${freshness.toFixed(1)}%`);
            }
        }
        
        harvestDate.addEventListener('change', calculateFreshness);
        shelfLife.addEventListener('input', calculateFreshness);
    }
    
    // Blockchain verification animation
    $('.blockchain-info').hover(function() {
        $(this).find('.text-monospace').text($(this).data('full-hash'));
    }, function() {
        const shortHash = $(this).data('full-hash').substring(0, 12) + '...';
        $(this).find('.text-monospace').text(shortHash);
    });
});