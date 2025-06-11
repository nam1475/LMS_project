$(function() {
    $('#thumbnail-input').on('change', function(event) {
        const selectedImage = document.getElementById('selectedAvatar');
        const fileInput = event.target;
    
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
    
            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };
    
            reader.readAsDataURL(fileInput.files[0]);
        }
    
        $('#remove-thumbnail').removeClass('d-none');
    });

    $('#remove-thumbnail').on('click', function () {
        const thumbnailInput = document.getElementById('thumbnail-input');
        const selectedAvatar = document.getElementById('selectedAvatar');
        const thumbnailDisplayDefault = document.getElementById('thumbnail-display-default');
        thumbnailInput.value = '';
        selectedAvatar.src = thumbnailDisplayDefault ? thumbnailDisplayDefault.value : '';
    });
    
});