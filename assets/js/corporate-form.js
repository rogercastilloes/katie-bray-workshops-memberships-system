document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('corporate-form');
    
    form?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        formData.append('action', 'kb_submit_corporate_lead');
        
        // Disable submit button
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = kbCorporateForm.sending;
        
        fetch(katieBrayAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                form.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-medium mb-2">${kbCorporateForm.success}</h3>
                        <p class="text-gray-600">${kbCorporateForm.thankYou}</p>
                    </div>
                `;
            } else {
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'p-4 bg-red-50 text-red-600 rounded-md mb-6';
                errorDiv.textContent = data.data.message || kbCorporateForm.error;
                form.insertBefore(errorDiv, form.firstChild);
                
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    });
});
