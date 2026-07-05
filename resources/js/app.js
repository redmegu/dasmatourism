import './bootstrap';
import 'bootstrap';
import Swal from 'sweetalert2';

// Make SweetAlert2 available globally
window.Swal = Swal;

// Toast configuration
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Confirm Delete Helper
window.confirmDelete = function(formId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Show success messages from session
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('[data-success]');
    if (successMessage) {
        Toast.fire({
            icon: 'success',
            title: successMessage.getAttribute('data-success')
        });
    }

    const errorMessage = document.querySelector('[data-error]');
    if (errorMessage) {
        Toast.fire({
            icon: 'error',
            title: errorMessage.getAttribute('data-error')
        });
    }

    const infoMessage = document.querySelector('[data-info]');
    if (infoMessage) {
        Toast.fire({
            icon: 'info',
            title: infoMessage.getAttribute('data-info')
        });
    }
});

// Image Preview
window.previewImage = function(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
};

// Star Rating
window.setRating = function(rating) {
    document.getElementById('rating-input').value = rating;
    
    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById('star-' + i);
        if (i <= rating) {
            star.classList.remove('bx-star');
            star.classList.add('bxs-star');
        } else {
            star.classList.remove('bxs-star');
            star.classList.add('bx-star');
        }
    }
};
