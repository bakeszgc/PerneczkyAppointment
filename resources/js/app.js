import './bootstrap';
import './calendar.js';

// RELLAX BG
import Rellax from 'rellax';

document.addEventListener('DOMContentLoaded', () => {
    new Rellax('.rellax-bg', {
        speed: -3,    // Adjust the speed as needed
        center: true, // Keeps the background centered
        round: true,  // Better performance with rounded values
    });
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



document.addEventListener('DOMContentLoaded', () => {

    // MODAL OPEN & CLOSE
    // const openButton = document.getElementById('openModal');
    const closeButton = document.getElementById('closeModal');
    const cropModal = document.getElementById('cropModal');

    // openButton.addEventListener('click', () => { toggleElement(cropModal); });
    if (closeButton) {
        closeButton.addEventListener('click', () => { toggleElement(cropModal); });
    }

    // SUBMIT BUTTON ACTIVATES ON INPUT OR SELECT CHANGE
    const submitButton = document.getElementById('submitButton');
    const inputs = document.querySelectorAll('input, select, textarea');

    if (submitButton) {
        submitButton.disabled = true;

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                submitButton.disabled = false;
            })
        });
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