document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de imágenes
    const imagenUpload = document.getElementById('imagen');
    const imagePreview = document.getElementById('image-preview');
    
    imagenUpload.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files.length > 1) {
            alert('Solo puedes subir una imagen.');
            this.value = '';
            return;
        }
        
        const file = this.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('rounded-lg', 'object-cover', 'w-full', 'h-48');
            imagePreview.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    });
});