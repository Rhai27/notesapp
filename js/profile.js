// script.js
window.onload = function() {
    // Select all elements with class 'prompt' and 'promptg'
    var prompts = document.querySelectorAll('.prompt, .promptg');

    // Loop through each prompt and display them
    prompts.forEach(function(prompt) {
        prompt.style.display = 'block';
    });

    // After 3 seconds, hide the prompts
    setTimeout(function() {
        prompts.forEach(function(prompt) {
            prompt.style.display = 'none';
        });
    }, 3000); // 3000 milliseconds = 3 seconds
};
