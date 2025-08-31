<?php
//session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/header.php';

$type = isset($_GET['type']) && in_array($_GET['type'], ['lost', 'found']) ? $_GET['type'] : 'lost';
$csrf_token = generate_csrf_token();
?>

<!-- Report Section -->
<section class="py-16 bg-white mt-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex">
                <a href="report.php?type=lost" class="report-tab <?php echo $type === 'lost' ? 'active bg-primary-700 text-white' : 'bg-gray-200 text-gray-700'; ?>">Lost Item</a>
                <a href="report.php?type=found" class="report-tab <?php echo $type === 'found' ? 'active bg-secondary-500 text-white' : 'bg-gray-200 text-gray-700'; ?>">Found Item</a>
            </div>
            
            <div class="form-container p-8">
                <form id="reportForm" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Item Category</label>
                            <select name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none" required>
                                <option value="">Select a category</option>
                                <option value="Phone">Phone</option>
                                <option value="Wallet/Purse">Wallet/Purse</option>
                                <option value="Keys">Keys</option>
                                <option value="Bag/Backpack">Bag/Backpack</option>
                                <option value="ID Card">ID Card</option>
                                <option value="Jewelry">Jewelry</option>
                                <option value="Documents">Documents</option>
                                <option value="Other">Other</option>
                            </select>
                            <p id="error-category" class="text-red-600 text-sm mt-1 hidden"></p>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Date & Time</label>
                            <input type="datetime-local" name="date_time" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none" required>
                            <p id="error-date_time" class="text-red-600 text-sm mt-1 hidden"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2 font-medium">Description</label>
                            <textarea name="description" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none" rows="3" placeholder="Color, brand, special marks, contents..." required></textarea>
                            <p id="error-description" class="text-red-600 text-sm mt-1 hidden"></p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2 font-medium">Location</label>
                            <input type="text" name="location" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none mb-4" placeholder="Where was it lost/found?" required>
                            <p id="error-location" class="text-red-600 text-sm mt-1 hidden"></p>
                            <div class="map-placeholder h-48 rounded-lg">
                                <div class="map-grid"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center p-4 bg-white bg-opacity-80 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-primary-500 text-3xl mb-2"></i>
                                        <p class="font-medium">Location Map Preview</p>
                                        <p class="text-sm text-gray-600">Google Maps integration</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2 font-medium">Contact Information</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="email" name="contact_email" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none" placeholder="Email address" <?php echo $type == 'found' ? 'required' : ''; ?>>
                                <p id="error-contact_email" class="text-red-600 text-sm mt-1 hidden"></p>
                                <input type="tel" name="contact_phone" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none" placeholder="Phone number" <?php echo $type == 'found' ? 'required' : ''; ?>>
                                <p id="error-contact_phone" class="text-red-600 text-sm mt-1 hidden"></p>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2 font-medium">Upload Image (Optional)</label>
                            <div class="border-2 border-dashed border-primary-100 rounded-lg p-8 text-center bg-primary-50">
                                <input type="file" name="image" id="imageUpload" class="hidden" accept="image/jpeg,image/png">
                                <i class="fas fa-cloud-upload-alt text-3xl text-primary-300 mb-3"></i>
                                <p class="text-gray-700 mb-1">Drag & drop or click to upload</p>
                                <p class="text-sm text-gray-500">JPEG, PNG up to 5MB</p>
                                <button type="button" class="mt-4 bg-primary-500 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition" onclick="document.getElementById('imageUpload').click()">
                                    Select File
                                </button>
                                <div id="imagePreview" class="mt-4 hidden">
                                    <img src="" class="w-32 h-32 object-cover rounded mx-auto" alt="Preview">
                                </div>
                                <p id="error-image" class="text-red-600 text-sm mt-1 hidden"></p>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 flex justify-between mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="anonymous" class="mr-2 h-5 w-5 text-primary-500 rounded focus:ring-primary-500">
                                <span class="text-gray-700">Post anonymously</span>
                            </label>
                            <button type="submit" class="bg-primary-500 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Report
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Image preview functionality
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showError('image', 'File size must be less than 5MB');
            this.value = '';
            return;
        }
        
        // Check file type
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            showError('image', 'Only JPEG and PNG files are allowed');
            this.value = '';
            return;
        }
        
        // Clear any previous errors
        hideError('image');
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Form submission handling
document.getElementById('reportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Basic validation
    let isValid = true;
    const formData = new FormData(this);
    
    // Validate required fields
    const requiredFields = ['category', 'date_time', 'description', 'location'];
    if (formData.get('type') === 'found') {
        requiredFields.push('contact_email', 'contact_phone');
    }
    
    requiredFields.forEach(field => {
        if (!formData.get(field)) {
            showError(field, 'This field is required');
            isValid = false;
        } else {
            hideError(field);
        }
    });
    
    if (!isValid) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
    
    try {
        const response = await fetch('report_process.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.text();
        
        if (result === 'success') {
            showAlert('Item reported successfully!', 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            showAlert('Error: ' + result, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Report';
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred. Please try again.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Report';
    }
});

function showError(field, message) {
    const errorElement = document.getElementById(`error-${field}`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
}

function hideError(field) {
    const errorElement = document.getElementById(`error-${field}`);
    if (errorElement) {
        errorElement.classList.add('hidden');
    }
}

function showAlert(message, type) {
    const alert = document.getElementById('alert');
    alert.textContent = message;
    alert.className = `alert alert-${type}`;
    alert.style.display = 'block';
    
    setTimeout(() => {
        alert.style.display = 'none';
    }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>