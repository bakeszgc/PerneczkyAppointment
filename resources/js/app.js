import './bootstrap';
import Rellax from 'rellax';

document.addEventListener('DOMContentLoaded', () => {
    new Rellax('.rellax-bg', {
        speed: -3,    // Adjust the speed as needed
        center: true, // Keeps the background centered
        round: true,  // Better performance with rounded values
    });
});

const rellax = new Rellax('.rellax-bg', {
    breakpoints: {
        576: true, // Disable Rellax below 576px
    },
});