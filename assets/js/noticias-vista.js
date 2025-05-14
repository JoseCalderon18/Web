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
    
    // Validación del formulario
    const form = document.getElementById('newsForm');
    
    form.addEventListener('submit', function(e) {
        const titulo = document.getElementById('titulo').value.trim();
        const contenido = document.getElementById('contenido').value.trim();
        const fecha = document.getElementById('fecha').value;
        const imagen = document.getElementById('imagen').files[0];
        
        if (!titulo) {
            e.preventDefault();
            alert('Por favor, ingresa un título para la noticia.');
            return;
        }
        
        if (!contenido) {
            e.preventDefault();
            alert('Por favor, escribe el contenido de la noticia.');
            return;
        }
        
        if (!fecha) {
            e.preventDefault();
            alert('Por favor, selecciona una fecha de publicación.');
            return;
        }
        
        if (!imagen) {
            e.preventDefault();
            alert('Por favor, selecciona una imagen para la noticia.');
            return;
        }
    });
});