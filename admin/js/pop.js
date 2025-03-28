// Function to open the pop-up with custom content
function openPopup(content) {
    console.log("Inside openPopup"); // Add this line for debugging
    var popup = document.getElementById('popup');
    var popupText = document.getElementById('popup-text');

    // Check if the elements are found
    if (popup && popupText) {
        console.log("Elements found:", popup, popupText);

        // Set the content
        popupText.innerHTML = content;

        // Display the popup
        popup.style.display = 'block';
        console.log("Popup displayed"); // Add this line for debugging
    } else {
        console.error("Elements 'popup' or 'popup-text' not found.");
    }
}



// Function to close the pop-up
// Function to close the pop-up and remove the 'show_popup' parameter
// Function to close the pop-up and remove the 'show_popup' parameter
function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none';

    // Get the current URL
    var currentUrl = window.location.href;

    // Check if the 'show_popup' parameter exists in the URL
    if (currentUrl.includes('show_popup=true')  ) {
        
        var updatedUrl = currentUrl.replace('show_popup=true', '');

        // Update the URL without causing a page reload
        history.replaceState(null, null, updatedUrl);
    }
    else if(currentUrl.includes('order_deleted=true')){
        var updatedUrl = currentUrl.replace('order_deleted=true', '')
        history.replaceState(null, null, updatedUrl);

    }
    else if(currentUrl.includes('logout=true')){
        var updatedUrl = currentUrl.replace('logout=true', '')
        history.replaceState(null, null, updatedUrl);

    }
}

