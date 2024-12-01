
// inactivity.js

let inactivityTimeout;

function resetInactivityTimer() {
    clearTimeout(inactivityTimeout);
    inactivityTimeout = setTimeout(logoutUser, 5 * 60 * 1000); // 10 minutes in milliseconds
}

function logoutUser() {
    // Redirect to logout endpoint or perform logout logic
    window.location.href = 'process.php?logout=true';
}

// Reset the inactivity timer on any user activity
document.addEventListener('mousemove', resetInactivityTimer);
document.addEventListener('keydown', resetInactivityTimer);

// Initialize the timer on page load
resetInactivityTimer();
