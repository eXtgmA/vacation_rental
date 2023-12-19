const optionCards = document.querySelectorAll('.option-card');
const modal = document.querySelector('.option-modal');
const modalName = document.querySelector('.option-modal .option-name h3');
const modalPrice = document.querySelector('.option-modal .option-price-value');
const modalDescription = document.querySelector('.option-modal .option-description p');
const modalImage = document.querySelector('.option-modal .option-image img');

// click event listener to each option card to show the modal
optionCards.forEach(function(optionCard) {
    optionCard.addEventListener('click', function() {
        // Update the modal content with the description text
        modalName.textContent = this.querySelector('.option-name h3').textContent;
        modalPrice.textContent = this.querySelector('.option-price-value').textContent;
        modalDescription.textContent = this.querySelector('.option-description p').textContent;
        modalImage.src = this.querySelector('.option-image img').src;

        // display the modal
        modal.style.display = 'block';
    });
});

// listeners to close the modal
document.querySelector('.close-button').addEventListener('click', function() {
    modal.style.display = 'none';
});
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        modal.style.display = 'none';
    }
});
