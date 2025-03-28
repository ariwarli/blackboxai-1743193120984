jQuery(document).ready(function($) {
    // Tab Switching Logic
    $('.tab-button').on('click', function() {
        const tabId = $(this).data('tab');
        
        // Update tab buttons
        $('.tab-button').removeClass('active border-blue-500 text-blue-600')
            .addClass('border-transparent text-gray-500');
        $(this).addClass('active border-blue-500 text-blue-600')
            .removeClass('border-transparent text-gray-500');
        
        // Update tab content
        $('.tab-content').addClass('hidden');
        $(`#${tabId}-tab`).removeClass('hidden');
    });

    // File Upload Preview
    $('#file-upload').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#training-data').val(e.target.result);
            };
            reader.readAsText(file);
        }
    });

    // Drag and Drop Functionality
    const dropZone = $('.border-dashed');
    
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-blue-500');
    });

    dropZone.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('border-blue-500');
    });

    dropZone.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-blue-500');
        
        const file = e.originalEvent.dataTransfer.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#training-data').val(e.target.result);
            };
            reader.readAsText(file);
        }
    });

    // Q&A Entry Management
    $('#add-qna').on('click', function() {
        const newEntry = `
            <div class="qna-entry bg-gray-50 p-4 rounded-lg">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Question</label>
                        <input type="text" name="questions[]" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Answer</label>
                        <textarea name="answers[]" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="remove-qna text-red-600 hover:text-red-800">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
        $('#qna-entries').append(newEntry);
    });

    $(document).on('click', '.remove-qna', function() {
        if ($('.qna-entry').length > 1) {
            $(this).closest('.qna-entry').remove();
        } else {
            alert('At least one Q&A entry is required.');
        }
    });

    // Form Submissions
    $('#training-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: sentraAiAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_training_data',
                nonce: sentraAiAjax.nonce,
                formData: Object.fromEntries(formData)
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Training data saved successfully!', 'success');
                } else {
                    showNotification('Error saving training data.', 'error');
                }
            },
            error: function() {
                showNotification('Server error occurred.', 'error');
            }
        });
    });

    $('#company-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: sentraAiAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_company_info',
                nonce: sentraAiAjax.nonce,
                formData: Object.fromEntries(formData)
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Company information saved successfully!', 'success');
                } else {
                    showNotification('Error saving company information.', 'error');
                }
            },
            error: function() {
                showNotification('Server error occurred.', 'error');
            }
        });
    });

    $('#qna-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: sentraAiAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_qna_data',
                nonce: sentraAiAjax.nonce,
                formData: Object.fromEntries(formData)
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Q&A data saved successfully!', 'success');
                } else {
                    showNotification('Error saving Q&A data.', 'error');
                }
            },
            error: function() {
                showNotification('Server error occurred.', 'error');
            }
        });
    });

    // Notification System
    function showNotification(message, type) {
        const notificationClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const notification = $(`
            <div class="fixed top-4 right-4 px-6 py-3 rounded-lg text-white ${notificationClass} shadow-lg transition-opacity duration-300">
                ${message}
            </div>
        `).appendTo('body');

        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});