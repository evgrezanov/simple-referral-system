document.addEventListener('DOMContentLoaded', function() {
    var clipboard = new ClipboardJS('.copy-button');
    
    clipboard.on('success', function(e) {
        var originalText = e.trigger.textContent;
        e.trigger.textContent = 'Copied!';
        setTimeout(function() {
            e.trigger.textContent = originalText;
        }, 2000);
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });
});