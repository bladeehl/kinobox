function showNotification(message) {
    const notification = document.getElementById("notification");
    if (notification) {
        notification.textContent = message;
        notification.style.display = "block";
        setTimeout(() => {
            notification.style.display = "none";
        }, 3000);
    }
}
