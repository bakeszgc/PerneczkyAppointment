import './bootstrap';

// RELLAX BG
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

// CROPPER.JS
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css'

let cropper;
document.addEventListener('DOMContentLoaded', () => {

    const imageInput = document.getElementById('selectedImg');
    const submitButton = document.getElementById('submit');
    const cropButton = document.getElementById('crop');
    const resetButton = document.getElementById('reset');
    const cropModal = document.getElementById('cropModal');
    const submitDiv = document.getElementById('submitDiv');

    if (imageInput) {
        imageInput.addEventListener('change', () => {
            submitButton.setAttribute("disabled","");

            if (typeof cropper != 'undefined') {
                console.log("Destroy previous cropper");
                cropper.destroy();
                cropper = null;
            }

            const file = imageInput.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgResult = e.target.result;

                    const image = document.getElementById('image');
                    image.src = imgResult;

                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 2,
                        minCropBoxWidth: 100,
                        minCropBoxHeight: 100,
                        rotatable: false,
                        crop(event) {
                            submitButton.setAttribute("disabled","");
                        },
                        preview: '.preview'
                    });
                    cropButton.removeAttribute("hidden","");
                    resetButton.removeAttribute("hidden","");

                    toggleElement(cropModal);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (cropButton) {
        cropButton.addEventListener("click", function() {
            let canvas = cropper.getCroppedCanvas();
            const title = document.getElementById('currentPfpTitle');

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'croppedImage.png',{type: blob.type});
                const dataTransfer = new DataTransfer();            
                dataTransfer.items.add(file);
                
                const croppedImageInput = document.querySelector("input[name='croppedImg']");
                croppedImageInput.files = dataTransfer.files;
                submitButton.removeAttribute("disabled","");
                submitDiv.removeAttribute("hidden","");
            });
            
            title.innerHTML = "Updated profile picture";
            toggleElement(cropModal);
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", function() {
            cropper.reset();
        });
    }
});

// MODAL OPEN & CLOSE
document.addEventListener('DOMContentLoaded', () => {
    // const openButton = document.getElementById('openModal');
    const closeButton = document.getElementById('closeModal');
    const cropModal = document.getElementById('cropModal');

    // openButton.addEventListener('click', () => { toggleElement(cropModal); });
    if (closeButton) {
        closeButton.addEventListener('click', () => { toggleElement(cropModal); });
    }
});

function toggleElement(element) {
    if (element) {
        element.classList.toggle('flex');
        element.classList.toggle('hidden');
    } else {
        console.error('Element not found or undefined.');
    }
}

// REMOVES DISABLED FROM SUBMIT BUTTON AFTER THE RADIO BUTTONS ARE CHECKED
document.addEventListener('DOMContentLoaded', () => {
    
    const dayRadioButtons = document.querySelectorAll('input[name="day"]');
    const barberRadioButtons = document.querySelectorAll('input[name="barber_id"]');
    const serviceRadioButtons = document.querySelectorAll('input[name="service_id"]');
    const submitButton = document.getElementById('ctaButton');

    if (submitButton) submitButton.disabled = true;

    checkDateRadioButtons(submitButton);

    if (dayRadioButtons) {
        dayRadioButtons.forEach(dayButton => {
            dayButton.addEventListener('change', function () {
                checkDateRadioButtons(submitButton);
            });
        });
    }

    if (barberRadioButtons && serviceRadioButtons) {

        checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);

        barberRadioButtons.forEach(barberButton => {
            barberButton.addEventListener('change', function () {
                checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);
            });
        });

        serviceRadioButtons.forEach(serviceButton => {
            serviceButton.addEventListener('change', function () {
                checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);
            });
        });
    }
});

function checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton) {
    const isAnyBarbersChecked = Array.from(barberRadioButtons).some(radio => radio.checked);
    const isAnyServicesChecked = Array.from(serviceRadioButtons).some(radio => radio.checked);
    if (submitButton) { submitButton.disabled = !isAnyBarbersChecked || !isAnyServicesChecked; }
}

function checkDateRadioButtons(submitButton) {
    const dateRadioButtons = document.querySelectorAll('input[name="date"]');

    if (dateRadioButtons) {
        dateRadioButtons.forEach(dateButton => {
            dateButton.addEventListener('change', function () {
                const isAnyDatesChecked = Array.from(dateRadioButtons).some(radio => radio.checked);
                if (submitButton) {
                    submitButton.disabled = !isAnyDatesChecked;
                }
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const calendarContainter = document.getElementById('calendarContainter');
    const nextMonthButton = document.getElementById('nextMonthButton');
    const previousMonthButton = document.getElementById('previousMonthButton');

    nextMonthButton.addEventListener('click', () => {
        calendarContainter.classList.add('-translate-x-1/4');
        nextMonthButton.setAttribute('disabled','');
        previousMonthButton.removeAttribute('disabled','');
    });

    previousMonthButton.addEventListener('click', () => {
        calendarContainter.classList.remove('-translate-x-1/4');
        previousMonthButton.setAttribute('disabled','');
        nextMonthButton.removeAttribute('disabled','');
    });
})