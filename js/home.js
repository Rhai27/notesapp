// Get the textarea element
const textarea = document.getElementById('noteDescription');

// Get the div element to display character count
const charCount = document.getElementById('charCount');

// Add event listener for input event
textarea.addEventListener('input', function() {
    // Get the length of the text inside the textarea
    const textLength = textarea.value.length;

    // Get the maximum character limit
    const maxLength = parseInt(textarea.getAttribute('maxlength'));

    // Update the character count display
    charCount.textContent = 'Characters: ' + textLength + ' / ' + maxLength;
});
