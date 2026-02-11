
function confirmAction(message) {
    return confirm(message);
}


function validateNumber(input) {
    if(input.value < 0) {
        alert("Value cannot be negative");
        input.value = 0;
        input.focus();
        return false;
    }
    return true;
}


function previewImage(input) {
    if(input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            
            let preview = document.getElementById('image-preview');
            if(!preview) {
                preview = document.createElement('img');
                preview.id = 'image-preview';
                preview.style.maxWidth = '200px';
                preview.style.maxHeight = '200px';
                preview.style.marginTop = '10px';
                input.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}


function confirmOrder(perfumeName, price) {
    return confirm(`Order ${perfumeName} for $${price}?`);
}


function confirmLogout() {
    return confirm("Are you sure you want to logout?");
}


document.addEventListener('DOMContentLoaded', function() {
   
    const deleteLinks = document.querySelectorAll('a[onclick*="confirm"]');
    deleteLinks.forEach(link => {
        link.onclick = function() {
            return confirm('Are you sure?');
        };
    });
    
   
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateNumber(this);
        });
    });
    
   
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            previewImage(this);
        });
    });
});
