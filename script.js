var images = ['dessert1.jpg', 'dessert2.jpg', 'dessert3.jpg', 'dessert6.jpg', 'dessert8.jpg']; // Array of image filenames
var currentIndex = 0;

// Create a div for the background image
var backgroundDiv = document.createElement('div');
backgroundDiv.style.position = 'fixed';
backgroundDiv.style.top = '0';
backgroundDiv.style.left = '0';
backgroundDiv.style.width = '100%';
backgroundDiv.style.height = '100%';
backgroundDiv.style.zIndex = '-1';
document.body.appendChild(backgroundDiv);

function changeBackground() {
    var backgroundImage = 'url(' + images[currentIndex] + ')';
    backgroundDiv.style.backgroundImage = backgroundImage;
    backgroundDiv.style.backgroundSize = 'cover'; // Adjust background size as needed
    backgroundDiv.style.filter = 'blur(5px)'; // Adjust blur strength as needed

    currentIndex = (currentIndex + 1) % images.length; // Loop through images
}

// Change background every 5 seconds
setInterval(changeBackground, 2000); // Change interval as needed (milliseconds)


