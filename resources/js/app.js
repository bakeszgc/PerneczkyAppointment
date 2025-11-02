import './bootstrap';
import './calendar.js';

// RELLAX BG
import Rellax from 'rellax';

// CROPPER.JS
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css'

let cropper;

document.addEventListener('DOMContentLoaded', () => {

    // RELLAX BG
    new Rellax('.rellax-bg', {
        speed: -3,    // Adjust the speed as needed
        center: true, // Keeps the background centered
        round: true,  // Better performance with rounded values
    });

    // CROPPER.JS
    const imageInput = document.getElementById('selectedImg');
    const cropperSubmitButton = document.getElementById('submit');
    const cropButton = document.getElementById('crop');
    const resetButton = document.getElementById('reset');
    const cropModal = document.getElementById('cropModal');
    const submitDiv = document.getElementById('submitDiv');

    if (imageInput) {
        imageInput.addEventListener('change', () => {
            cropperSubmitButton.setAttribute("disabled","");

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
                        autoCropArea: 1,
                        viewMode: 2,
                        responsive: true,
                        minCropBoxWidth: 25,
                        minCropBoxHeight: 25,
                        rotatable: false,
                        dragMode: 'move',
                        crop(event) {
                            cropperSubmitButton.setAttribute("disabled","");
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
            let canvas = cropper.getCroppedCanvas({
                width: 800,
                height: 800,
                imageSmoothingQuality: 'high'
            });
            const title = document.getElementById('currentPfpTitle');

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'croppedImage.jpg',{type: blob.type});
                const dataTransfer = new DataTransfer();            
                dataTransfer.items.add(file);
                
                const croppedImageInput = document.querySelector("input[name='croppedImg']");
                croppedImageInput.files = dataTransfer.files;
                cropperSubmitButton.removeAttribute("disabled","");
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

    // MODAL OPEN & CLOSE
    const closeButton = document.getElementById('closeModal');
    if (closeButton) {
        closeButton.addEventListener('click', () => { toggleElement(cropModal); });
    }

    // ENABLING SUBMIT BUTTON AFTER FORM INPUT EVENT
    const submitButton = document.getElementById('submitButton');
    if (submitButton) enableButton(submitButton,null);
});

function toggleElement(element) {
    if (element) {
        element.classList.toggle('flex');
        element.classList.toggle('hidden');
    } else {
        console.error('Element not found or undefined.');
    }
}

// ENABLES BUTTON AFTER AN INPUT HAS CHANGED
window.enableButton = function(button, inputs) {
    if (inputs == null || inputs.length == 0) {
        inputs = document.querySelectorAll('input, select, textarea');
    }

    if (button == null) {
        button = document.getElementById('submitButton');
    }    

    if (button) {
        button.disabled = true;

        inputs.forEach(input => {            
            input.addEventListener('input', function () {
               button.disabled = false; 
            });
        });
    }    
};

// ENABLES BUTTON AFTER INPUTS ARE FILLED
window.enableButtonIfInputsFilled = function(button, inputs, reqInputs) {   

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            const allFilled = allInputsFilled(reqInputs);
            if (allFilled) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        });
    });
};

// RETURNS TRUE IF ALL INPUTS ARE FILLED
window.allInputsFilled = function (inputs) {
    let filled = 0;

    inputs.forEach(i => {
        if (i.type == 'checkbox') {
            if (i.checked) filled++;
        } else {
            if (i.value !== '') filled++;
        }
    });

    return filled === inputs.length;
}

// COUNTS CHARACTERS OF THE DESCRIPTION TEXTAREA
window.countCharacters = function (charCount, description) {
    charCount.innerHTML = description.value.length;

    description.addEventListener('input', function() {
        charCount.innerHTML = description.value.length;
    });
};