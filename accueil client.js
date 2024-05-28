let currentIndex = 0;

function showNextImage() {
    const carouselContainer = document.querySelector('.carousel-container');
    const images = carouselContainer.querySelectorAll('img');
    currentIndex = (currentIndex + 1) % images.length;
    carouselContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
}

setInterval(showNextImage, 3000);
