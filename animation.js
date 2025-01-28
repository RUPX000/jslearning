
<div class="welcome-container">
<div id="welcomeText" class="welcome-text">
 Welcome
</div>
</div>

// Array of languages with the word "Welcome"
const languages = [
 "Welcome",        // English
 "स्वागत छ",      // Nepali
 "स्वागत है",      // Hindi
 "ようこそ",       // Japanese
 "Willkommen"      // German
];

let index = 0;
const welcomeElement = document.getElementById('welcomeText');

// Function to change the text every 4 seconds
function changeLanguage() {
 welcomeElement.classList.remove('language-change'); // Reset the animation
 setTimeout(() => {
   welcomeElement.textContent = languages[index];
   welcomeElement.classList.add('language-change'); // Add animation to new text
 }, 500); // Small delay to allow reset of animation

 index = (index + 1) % languages.length; // Loop through the languages
}

// Call changeLanguage every 4 seconds
setInterval(changeLanguage, 4000);

// Initial call to set up the first language
changeLanguage();
