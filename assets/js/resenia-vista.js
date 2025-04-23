document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars svg');
    const ratingInput = document.getElementById('rating');
    const ratingValue = document.getElementById('ratingValue');
    
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            highlightStars(rating);
        });
        
        star.addEventListener('mouseout', function() {
            const currentRating = ratingInput.value || 0;
            highlightStars(currentRating);
        });
        
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            ratingValue.textContent = rating;
            highlightStars(rating);
        });
    });
    
    function highlightStars(rating) {
        stars.forEach(star => {
            const starRating = parseFloat(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
    
    // Vista previa de imágenes
    const photoUpload = document.getElementById('photo-upload');
    const imagePreview = document.getElementById('image-preview');
    
    photoUpload.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files.length > 3) {
            alert('Solo puedes subir un máximo de 3 imágenes.');
            this.value = '';
            return;
        }
        
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded-lg', 'object-cover', 'w-full', 'h-24');
                imagePreview.appendChild(img);
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Validación del formulario
    const form = document.getElementById('form-resenia');
    
    form.addEventListener('submit', function(e) {
        const rating = ratingInput.value;
        const comment = document.getElementById('comment').value.trim();
        
        if (!rating) {
            e.preventDefault();
            alert('Por favor, selecciona una puntuación.');
            return;
        }
        
        if (!comment) {
            e.preventDefault();
            alert('Por favor, escribe un comentario.');
            return;
        }
    });
});