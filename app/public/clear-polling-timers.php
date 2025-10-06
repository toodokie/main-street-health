<?php
/**
 * Clear all auto-polling that's causing 99% CPU usage
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clear Auto-Polling Timers</title>
</head>
<body>
    <h1>🛑 Clear All Auto-Polling Timers</h1>
    <p>This will stop all JavaScript timers that are causing 99% CPU usage.</p>

    <script>
    // Clear ALL setInterval timers
    const maxTimerId = setTimeout(() => {}, 0);
    for (let i = 1; i <= maxTimerId; i++) {
        clearInterval(i);
        clearTimeout(i);
    }

    // Clear specific intervals if they exist
    if (typeof progressInterval !== 'undefined') {
        clearInterval(progressInterval);
    }

    if (typeof window.Index !== 'undefined' && window.Index.scanState && window.Index.scanState.pollingTimer) {
        clearInterval(window.Index.scanState.pollingTimer);
    }

    document.write('<h2>✅ All timers cleared!</h2>');
    document.write('<p>Auto-polling should now be stopped.</p>');
    document.write('<p><a href="http://main-street-health.local/">Return to WordPress</a></p>');
    </script>
</body>
</html>