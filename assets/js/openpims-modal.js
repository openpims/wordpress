(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal
        var modal = document.getElementById('openpimsModal');

        if (!modal) {
            return;
        }

        // Get the <span> element that closes the modal (if exists)
        var span = modal.querySelector('.openpims-close');

        // Show modal if configured to do so
        if (window.openpimsSettings && window.openpimsSettings.showModal) {
            modal.style.display = 'block';
        }

        // When the user clicks on <span> (x), close the modal
        if (span) {
            span.onclick = function() {
                modal.style.display = 'none';
            }
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    });
})();