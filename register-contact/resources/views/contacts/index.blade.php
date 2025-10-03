<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .contact-card {
            transition: transform 0.2s;
        }
        .contact-card:hover {
            transform: translateY(-2px);
        }
        .search-box {
            max-width: 400px;
        }
        .btn-action {
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-address-book"></i> Quản lý Contact</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal" onclick="openCreateModal()">
                        <i class="fas fa-plus"></i> Thêm Contact
                    </button>
                </div>

                <!-- Search Box -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group search-box">
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm contact...">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchContacts()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-outline-primary" onclick="loadContacts()">
                            <i class="fas fa-refresh"></i> Làm mới
                        </button>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Contacts List -->
                <div id="contactsList" class="row">
                    <!-- Contacts will be loaded here -->
                </div>

                <!-- Pagination -->
                <nav aria-label="Contacts pagination" class="mt-4">
                    <ul id="pagination" class="pagination justify-content-center">
                        <!-- Pagination will be generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailModalLabel">Gửi Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="emailForm">
                        <input type="hidden" id="emailContactId" name="contact_id">
                        <div class="mb-3">
                            <label class="form-label">Gửi tới</label>
                            <input type="text" id="emailTo" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" id="emailSubject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea id="emailMessage" class="form-control" rows="6" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" onclick="sendEmail()">
                        <i class="fas fa-paper-plane"></i> Gửi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Thêm Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        <input type="hidden" id="contactId" name="id">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="0123456789">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveContact()">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa contact này không?</p>
                    <div id="deleteContactInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inject Authorization header into all fetch calls
        (function() {
            const originalFetch = window.fetch;
            window.fetch = function(input, init) {
                init = init || {};
                init.headers = init.headers || {};
                try {
                    const token = localStorage.getItem('access_token');
                    if (token) {
                        init.headers['Authorization'] = `Bearer ${token}`;
                    }
                } catch (e) {}
                init.headers['Accept'] = init.headers['Accept'] || 'application/json';
                return originalFetch(input, init);
            };
        })();

        let currentPage = 1;
        let deleteContactId = null;
        let emailContactId = null;

        // Helper function to handle 401 responses
        function handleUnauthorized(response) {
            if (response.status === 401) {
                window.location.href = '/login';
                return true;
            }
            return false;
        }

        // Load contacts on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadContacts();
            
            // Debug: Check if email modal elements exist
            console.log('Email Contact ID input:', document.getElementById('emailContactId'));
            console.log('Email Modal:', document.getElementById('emailModal'));
        });

        // Load contacts
        async function loadContacts(page = 1) {
            showLoading(true);
            try {
                const response = await fetch(`/api/contact?paginated=true&per_page=6&page=${page}`);
                
                if (handleUnauthorized(response)) return;
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    displayContacts(data.data);
                    displayPagination(data.pagination);
                    currentPage = page;
                } else {
                    showAlert('Lỗi: ' + data.message, 'danger');
                }
            } catch (error) {
                showAlert('Lỗi khi tải danh sách contacts: ' + error.message, 'danger');
            }
            showLoading(false);
        }

        // Display contacts
        function displayContacts(contacts) {
            const container = document.getElementById('contactsList');
            
            if (contacts.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Không có contact nào</h4>
                        <p class="text-muted">Hãy thêm contact đầu tiên của bạn!</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = contacts.map(contact => `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card contact-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user"></i> ${contact.name}
                            </h5>
                            <p class="card-text">
                                <i class="fas fa-envelope"></i> ${contact.email}<br>
                                ${contact.phone ? `<i class="fas fa-phone"></i> ${contact.phone}<br>` : ''}
                                ${contact.address ? `<i class="fas fa-map-marker-alt"></i> ${contact.address}` : ''}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn btn-sm btn-outline-primary btn-action" onclick="editContact(${contact.id})">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-outline-success btn-action" onclick="sendQuickEmail(${contact.id})">
                                <i class="fas fa-envelope"></i> Email
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteContact(${contact.id}, '${contact.name}')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Display pagination
        function displayPagination(pagination) {
            const container = document.getElementById('pagination');
            let html = '';

            // Previous button
            if (pagination.current_page > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadContacts(${pagination.current_page - 1})">Trước</a></li>`;
            }

            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                const activeClass = i === pagination.current_page ? 'active' : '';
                html += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="loadContacts(${i})">${i}</a></li>`;
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadContacts(${pagination.current_page + 1})">Sau</a></li>`;
            }

            container.innerHTML = html;
        }

        // Search contacts
        async function searchContacts() {
            const keyword = document.getElementById('searchInput').value.trim();
            if (!keyword) {
                loadContacts();
                return;
            }
            showLoading(true);
            try {
                const response = await fetch(`/api/contact/search?q=${encodeURIComponent(keyword)}&paginated=true&per_page=6`);
                
                if (handleUnauthorized(response)) return;
                
                const data = await response.json();
                if (data.status === 'success') {
                    displayContacts(data.data);
                    displayPagination(data.pagination);
                } else {
                    showAlert('Lỗi: ' + data.message, 'danger');
                }
            } catch (error) {
                showAlert('Lỗi khi tìm kiếm: ' + error.message, 'danger');
            }
            showLoading(false);
        }

        // Open create modal
        function openCreateModal() {
            document.getElementById('contactModalLabel').textContent = 'Thêm Contact';
            document.getElementById('contactForm').reset();
            document.getElementById('contactId').value = '';
        }

        // Send quick email without modal
        async function sendQuickEmail(id) {
            const payload = {
                subject: 'Thông báo từ hệ thống',
                message: 'Xin chào, hello.'
            };

            try {
                const response = await fetch(`/api/contact/${id}/email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (handleUnauthorized(response)) return;

                const data = await response.json();
                if (response.ok && data.status === 'success') {
                    showAlert('Gửi email thành công!', 'success');
                } else {
                    showAlert('Gửi email thất bại: ' + (data.message || 'Có lỗi xảy ra'), 'danger');
                }
            } catch (error) {
                showAlert('Lỗi khi gửi email: ' + error.message, 'danger');
            }
        }

        // Edit contact
        async function editContact(id) {
            try {
                const response = await fetch(`/api/contact/${id}`);
                
                if (handleUnauthorized(response)) return;
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    const contact = data.data;
                    document.getElementById('contactModalLabel').textContent = 'Sửa Contact';
                    document.getElementById('contactId').value = contact.id;
                    document.getElementById('name').value = contact.name;
                    document.getElementById('email').value = contact.email;
                    document.getElementById('phone').value = contact.phone || '';
                    document.getElementById('address').value = contact.address || '';
                    
                    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
                    modal.show();
                } else {
                    showAlert('Lỗi: ' + data.message, 'danger');
                }
            } catch (error) {
                showAlert('Lỗi khi tải thông tin contact: ' + error.message, 'danger');
            }
        }

        // Save contact
        async function saveContact() {
            const form = document.getElementById('contactForm');
            const formData = new FormData(form);
            const contactData = Object.fromEntries(formData);
            
            // Client-side validation
            if (!contactData.name || contactData.name.trim().length < 2) {
                showAlert('Tên phải có ít nhất 2 ký tự', 'danger');
                return;
            }
            
            if (!contactData.email || !isValidEmail(contactData.email)) {
                showAlert('Email không đúng định dạng', 'danger');
                return;
            }
            
            if (contactData.phone && !isValidPhone(contactData.phone)) {
                showAlert('Số điện thoại phải có 10-11 chữ số', 'danger');
                return;
            }
            
            // Remove empty fields
            Object.keys(contactData).forEach(key => {
                if (contactData[key] === '') {
                    delete contactData[key];
                }
            });

            const isEdit = contactData.id;
            const url = isEdit ? `/api/contact/${contactData.id}` : '/api/contact';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                console.log('CSRF Token:', csrfToken); // Debug log
                console.log('Request Data:', contactData); // Debug log
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(contactData)
                });

                if (handleUnauthorized(response)) return;

                const data = await response.json();
                
                console.log('API Response:', data); // Debug log
                console.log('Response Status:', response.status); // Debug log
                
                if (response.ok && data.status === 'success') {
                    showAlert(isEdit ? 'Cập nhật contact thành công!' : 'Tạo contact thành công!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
                    loadContacts(currentPage);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Lỗi validation:\n';
                        Object.keys(data.errors).forEach(field => {
                            errorMessage += `• ${data.errors[field].join(', ')}\n`;
                        });
                        showAlert(errorMessage, 'danger');
                    } else {
                        showAlert('Lỗi: ' + (data.message || 'Có lỗi xảy ra'), 'danger');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Lỗi khi lưu contact: ' + error.message, 'danger');
            }
        }

        // Delete contact
        function deleteContact(id, name) {
            deleteContactId = id;
            document.getElementById('deleteContactInfo').innerHTML = `
                <strong>${name}</strong>
            `;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Confirm delete
        async function confirmDelete() {
            if (!deleteContactId) return;

            try {
                const response = await fetch(`/api/contact/${deleteContactId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (handleUnauthorized(response)) return;

                const data = await response.json();
                
                if (data.status === 'success') {
                    showAlert('Xóa contact thành công!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    loadContacts(currentPage);
                } else {
                    showAlert('Lỗi: ' + data.message, 'danger');
                }
            } catch (error) {
                showAlert('Lỗi khi xóa contact: ' + error.message, 'danger');
            }
            
            deleteContactId = null;
        }



        // Show loading spinner
        function showLoading(show) {
            document.getElementById('loadingSpinner').style.display = show ? 'block' : 'none';
        }

        // Show alert
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; white-space: pre-line;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Auto remove after 8 seconds for validation errors
            const timeout = type === 'danger' ? 8000 : 5000;
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, timeout);
        }

        // Validation helper functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPhone(phone) {
            const phoneRegex = /^[0-9]{10,11}$/;
            return phoneRegex.test(phone);
        }

        // Search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchContacts();
            }
        });
    </script>
</body>
</html>
