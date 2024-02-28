/* Copy your JavaScript code here */
function togglePopup() {
    var popup = document.getElementById('popup');
    if (popup.style.display === 'block') {
        popup.style.display = 'none';
    } else {
        popup.style.display = 'block';
    }
}

function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none';
}
