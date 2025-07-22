<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unified Dashboard</title>
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-color: #dee2e6;
            --font-family-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --border-radius: 0.375rem;
            --box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        body {
            font-family: var(--font-family-sans-serif);
            line-height: 1.5;
            margin: 0;
            background-color: #f0f2f5;
            color: var(--dark-color);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 2rem;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 1140px;
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        .section { display: none; }
        .active-section { display: block; }

        h1, h2, h3, h4 { color: #343a40; margin-top: 0; margin-bottom: 0.5rem; font-weight: 500; }
        h1 { font-size: 2.5rem; }
        h2 { font-size: 2rem; }
        h3 { font-size: 1.75rem; }
        
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input, select {
            display: block;
            width: 100%;
            padding: .5rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            box-sizing: border-box;
        }
        input:focus, select:focus {
             border-color: #86b7fe;
             outline: 0;
             box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        input[type="file"] {
            padding: .3rem .5rem;
        }

        button {
            display: inline-block;
            font-weight: 500;
            line-height: 1.5;
            color: #fff;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: .5rem 1rem;
            font-size: 1rem;
            border-radius: var(--border-radius);
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            width: auto;
        }
        button:hover { background-color: #0b5ed7; border-color: #0a58ca; }
        
        button.button-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        button.button-secondary:hover { background-color: #5c636a; border-color: #565e64; }

        form > button { width: 100%; margin-top: 1rem; }

        table { border-collapse: collapse; width: 100%; margin-top: 1em; border-color: var(--border-color); }
        th, td { border: 1px solid var(--border-color); padding: .75rem; text-align: left; vertical-align: top; }
        th { background-color: var(--light-color); font-weight: 500; }
        
        pre { background-color: #e9ecef; padding: 1em; border-radius: 5px; white-space: pre-wrap; word-wrap: break-word; font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .error { color: var(--danger-color); min-height: 1.2em; font-size: 0.875em; }
        
        .user-info { padding: 1rem; background: var(--light-color); border-radius: var(--border-radius); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .user-info button { width: auto; }

        .tab-nav { border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem; display: flex; gap: .5rem; }
        .tab-nav button {
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--secondary-color);
            padding: 1rem .5rem;
            margin-bottom: -1px;
            border-radius: 0;
            font-weight: 500;
        }
        .tab-nav button.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }
        .tab-nav button:hover {
            color: var(--primary-color);
            background: none;
            border-color: transparent;
            border-bottom-color: var(--primary-color);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn .5s; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .card { 
            border: 1px solid var(--border-color); 
            padding: 1.5rem; 
            margin-bottom: 1rem; 
            border-radius: var(--border-radius);
            background-color: #fff;
        }
        #aspirations-container .card {
             background-color: var(--light-color);
             border-color: #e9ecef;
        }

        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; border-radius: var(--border-radius); box-shadow: var(--box-shadow); }
        .close-modal { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        
        #suggestion-tab > .card {
            max-width: 500px;
            margin: auto;
        }
        #auth-tab > .card {
            max-width: 500px;
            margin: auto;
        }

        #subjects-container > div {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            background: #fafafa;
        }
        #subjects-container > div > input {
            margin-bottom: 0.5rem;
        }
        hr {
            margin: 2rem 0;
            color: inherit;
            background-color: currentColor;
            border: 0;
            opacity: .25;
            height: 1px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Unified Testing Dashboard</h1>

    <!-- Logged Out View -->
    <div id="logged-out-view" class="active-section section">
        <nav class="tab-nav">
            <button onclick="showLoggedOutTab('suggestion')" class="active">Gợi ý ngành học</button>
            <button onclick="showLoggedOutTab('auth')">Đăng nhập / Đăng ký</button>
        </nav>

        <div id="suggestion-tab" class="tab-content active">
            <div class="card">
                <h2>Gợi ý ngành học theo điểm</h2>
                <form id="suggestion-form">
                    <label for="admission_method">Phương thức xét tuyển:</label>
                    <select name="admission_method" id="admission_method">
                        <option value="exam_score">Điểm thi THPT</option>
                        <option value="transcript_score">Học bạ</option>
                    </select>
                    <div id="subjects-container">
                        <div>
                            <label>Môn 1:</label>
                            <input type="text" name="scores[0][subject_name]" placeholder="e.g., Toán" required>
                            <input type="number" step="0.1" name="scores[0][score]" placeholder="e.g., 8.5" required>
                        </div>
                         <div>
                            <label>Môn 2:</label>
                            <input type="text" name="scores[1][subject_name]" placeholder="e.g., Lý" required>
                            <input type="number" step="0.1" name="scores[1][score]" placeholder="e.g., 8.0" required>
                        </div>
                         <div>
                            <label>Môn 3:</label>
                            <input type="text" name="scores[2][subject_name]" placeholder="e.g., Hóa" required>
                            <input type="number" step="0.1" name="scores[2][score]" placeholder="e.g., 7.5" required>
                        </div>
                    </div>
                    <button type="submit">Tìm ngành gợi ý</button>
                </form>
            </div>
        </div>

        <div id="auth-tab" class="tab-content">
            <div id="auth-section" class="card">
                 <h2>Đăng nhập hoặc Đăng ký</h2>
                 <!-- Login Form -->
                 <form id="login-form">
                     <h3>Đăng nhập</h3>
                     <p>Admin: <code>admin@example.com</code> / Student: <code>student@example.com</code>. Pass: <code>password</code></p>
                     <div><label for="login-email">Email:</label><input type="email" id="login-email" name="email" required></div>
                     <div><label for="login-password">Password:</label><input type="password" id="login-password" name="password" required></div>
                     <button type="submit">Đăng nhập</button>
                     <p class="error" id="login-error"></p>
                 </form>
                 <hr>
                 <!-- Register Form -->
                 <form id="register-form">
                     <h3>Đăng ký (Thí sinh)</h3>
                     <div><label for="register-name">Tên:</label><input type="text" id="register-name" name="name" required></div>
                     <div><label for="register-email">Email:</label><input type="email" id="register-email" name="email" required></div>
                     <div><label for="register-password">Mật khẩu:</label><input type="password" id="register-password" name="password" required></div>
                     <div><label for="register-password-confirmation">Xác nhận mật khẩu:</label><input type="password" id="register-password-confirmation" name="password_confirmation" required></div>
                     <button type="submit">Đăng ký & Đăng nhập</button>
                     <p class="error" id="register-error"></p>
                 </form>
            </div>
        </div>
    </div>
    
    <!-- Logged In View -->
    <div id="logged-in-view" class="section">
        <div class="user-info">
            <span>Welcome, <b id="user-info-span"></b></span>
            <button onclick="logout()">Đăng xuất</button>
        </div>
        
        <!-- Admin Section -->
        <div id="admin-section" class="section">
             <h2>Quản lý hồ sơ</h2>
             <div style="margin-bottom: 1em; display:flex; gap: 1em; align-items: center;">
                 <select id="status-filter" style="width: 200px;">
                     <option value="">Tất cả trạng thái</option>
                     <option value="pending">Pending</option>
                     <option value="processing">Processing</option>
                     <option value="approved">Approved</option>
                     <option value="rejected">Rejected</option>
                 </select>
                 <button onclick="fetchApplications()">Lọc</button>
             </div>
             <table>
                 <thead><tr><th>ID</th><th>User</th><th>Trạng thái</th><th>Ngày nộp</th><th>Hành động</th></tr></thead>
                 <tbody id="applications-table-body"></tbody>
             </table>
             <p class="error" id="admin-api-error"></p>
        </div>

        <!-- Student Section -->
        <div id="student-section" class="section">
            <nav class="tab-nav">
                <button onclick="showStudentTab('submit')" class="active">Nộp hồ sơ</button>
                <button onclick="showStudentTab('list')">Hồ sơ của tôi</button>
                <button onclick="showStudentTab('notifications')">Thông báo</button>
            </nav>

            <div id="student-submit-tab" class="tab-content active">
                <h3>Nộp hồ sơ mới</h3>
                 <form id="upload-form">
                     <div id="aspirations-container">
                         <div class="card">
                             <h4>Nguyện vọng 1</h4>
                             <label for="major_id_1">Major ID (e.g., 1 for IT, 2 for Business):</label>
                             <input type="number" name="aspirations[0][major_id]" id="major_id_1" value="1" required>
                             <label for="priority_1">Ưu tiên:</label>
                             <input type="number" name="aspirations[0][priority]" id="priority_1" value="1" min="1" required>
                         </div>
                     </div>
                     <button type="button" onclick="addAspiration()" class="button-secondary">Thêm nguyện vọng</button>
                     <hr style="margin: 2em 0;">
                     <label for="hoc_ba">Học bạ (PDF/Image):</label>
                     <input type="file" name="documents[hoc_ba]" id="hoc_ba" required>
                     <label for="cccd">CCCD (PDF/Image):</label>
                     <input type="file" name="documents[cccd]" id="cccd" required>
                     <label for="bang_tot_nghiep">Bằng tốt nghiệp (PDF/Image):</label>
                     <input type="file" name="documents[bang_tot_nghiep]" required>
                     <button type="submit">Nộp hồ sơ</button>
                 </form>
            </div>
            <div id="student-list-tab" class="tab-content">
                <h3>Danh sách hồ sơ đã nộp</h3>
                <table id="my-applications-table">
                     <thead><tr><th>ID</th><th>Trạng thái</th><th>Ngày nộp</th><th>Hành động</th></tr></thead>
                     <tbody id="my-applications-table-body"></tbody>
                </table>
            </div>
             <div id="student-notifications-tab" class="tab-content">
                <h3>Thông báo của bạn</h3>
                <div id="notifications-list"></div>
            </div>
        </div>
    </div>

    <!-- API Response Area -->
    <div id="response-container" class="card" style="margin-top: 2em; display: none;">
        <h3>API Response</h3>
        <pre id="response-pre"></pre>
    </div>
</div>

<script>
    // State
    let apiToken = null;
    let currentUser = null;
    let aspirationCount = 1;

    // --- DOM Elements ---
    const views = { loggedOut: document.getElementById('logged-out-view'), loggedIn: document.getElementById('logged-in-view') };
    const sections = { admin: document.getElementById('admin-section'), student: document.getElementById('student-section') };
    const loggedOutTabs = { suggestion: document.getElementById('suggestion-tab'), auth: document.getElementById('auth-tab') };
    const studentTabs = { submit: document.getElementById('student-submit-tab'), list: document.getElementById('student-list-tab'), notifications: document.getElementById('student-notifications-tab') };
    
    // Forms
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const suggestionForm = document.getElementById('suggestion-form');
    const uploadForm = document.getElementById('upload-form');

    // Display elements
    const responsePre = document.getElementById('response-pre');
    const adminApiError = document.getElementById('admin-api-error');

    // --- UI Logic ---
    function showView(viewName) {
        Object.values(views).forEach(v => v.classList.remove('active-section'));
        if(views[viewName]) views[viewName].classList.add('active-section');
    }
    
    function showLoggedOutTab(tabName) {
        document.querySelectorAll('#logged-out-view .tab-nav button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`#logged-out-view .tab-nav button[onclick="showLoggedOutTab('${tabName}')"]`).classList.add('active');
        Object.values(loggedOutTabs).forEach(tab => tab.classList.remove('active'));
        if(loggedOutTabs[tabName]) loggedOutTabs[tabName].classList.add('active');
    }

    function showStudentTab(tabName) {
        document.querySelectorAll('#student-section .tab-nav button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`#student-section .tab-nav button[onclick="showStudentTab('${tabName}')"]`).classList.add('active');
        Object.values(studentTabs).forEach(tab => tab.classList.remove('active'));
        if(studentTabs[tabName]) studentTabs[tabName].classList.add('active');
    }

    function displayResponse(data, title = "API Response") {
        const responseContainer = document.getElementById('response-container');
        responseContainer.style.display = 'block';
        responseContainer.querySelector('h3').textContent = title;
        responsePre.textContent = JSON.stringify(data, null, 2);
    }

    // --- API Calls ---
    async function makeApiCall(endpoint, options = {}) {
        const headers = { 'Accept': 'application/json' };
        if (apiToken) {
            headers['Authorization'] = `Bearer ${apiToken}`;
        }
        if (!(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
        }

        const response = await fetch(`/api${endpoint}`, { ...options, headers });
        const responseData = await response.json().catch(() => ({ message: `Received non-JSON response from server (Status: ${response.status})` }));

        if (!response.ok) {
            const message = responseData?.message || `HTTP Error ${response.status}`;
            const errors = responseData?.errors ? '\n' + Object.values(responseData.errors).flat().join('\n') : '';
            throw new Error(message + errors);
        }
        return responseData;
    }

    // --- Authentication Logic ---
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(loginForm).entries());
        try {
            const result = await makeApiCall('/login', { method: 'POST', body: JSON.stringify(data) });
            handleAuthSuccess(result);
        } catch (error) {
            document.getElementById('login-error').textContent = error.message;
        }
    });

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(registerForm).entries());
        try {
            const result = await makeApiCall('/register', { method: 'POST', body: JSON.stringify(data) });
            handleAuthSuccess(result);
        } catch (error) {
            document.getElementById('register-error').textContent = error.message;
        }
    });

    function handleAuthSuccess(data) {
        apiToken = data.access_token;
        currentUser = data.user;
        document.getElementById('user-info-span').textContent = `${currentUser.name} (${currentUser.email})`;
        showView('loggedIn');

        if (currentUser.is_admin) {
            sections.student.classList.remove('active-section');
            sections.admin.classList.add('active-section');
            fetchApplications();
        } else {
            sections.admin.classList.remove('active-section');
            sections.student.classList.add('active-section');
            showStudentTab('submit');
            fetchMyApplications();
            fetchNotifications();
        }
    }

    function logout() {
        apiToken = null;
        currentUser = null;
        showView('loggedOut');
    }

    // --- Suggestion Logic ---
    suggestionForm.addEventListener('submit', async(e) => {
        e.preventDefault();
        const formData = new FormData(suggestionForm);
        const data = {
            admission_method: formData.get('admission_method'),
            scores: [
                { subject_name: formData.get('scores[0][subject_name]'), score: formData.get('scores[0][score]') },
                { subject_name: formData.get('scores[1][subject_name]'), score: formData.get('scores[1][score]') },
                { subject_name: formData.get('scores[2][subject_name]'), score: formData.get('scores[2][score]') },
            ]
        };
        try {
            const result = await makeApiCall('/suggestions/by-score', { method: 'POST', body: JSON.stringify(data) });
            displayResponse(result, "Suggestion Result");
        } catch(error) {
            displayResponse({ error: error.message }, "Suggestion Error");
        }
    });

    // --- Admin Logic ---
    async function fetchApplications() {
        const status = document.getElementById('status-filter').value;
        const url = status ? `/admin/applications?status=${status}` : '/admin/applications';
        try {
            const data = await makeApiCall(url);
            const tableBody = document.getElementById('applications-table-body');
            tableBody.innerHTML = '';
            const applications = data?.data || [];
            if(applications.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5">No applications found.</td></tr>';
                return;
            }
            applications.forEach(app => {
                const row = `<tr>
                    <td>${app.id}</td>
                    <td>${app.user.name}</td>
                    <td><select onchange="updateStatus(${app.id}, this.value)">
                        <option value="pending" ${app.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="processing" ${app.status === 'processing' ? 'selected' : ''}>Processing</option>
                        <option value="approved" ${app.status === 'approved' ? 'selected' : ''}>Approved</option>
                        <option value="rejected" ${app.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                    </select></td>
                    <td>${new Date(app.submitted_at).toLocaleString()}</td>
                    <td><button onclick="fetchApplicationDetails(${app.id})">View Details</button></td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        } catch (error) { adminApiError.textContent = error.message; }
    }
     async function updateStatus(id, status) {
        try {
            await makeApiCall(`/admin/applications/${id}/status`, { method: 'PATCH', body: JSON.stringify({ status }) });
            fetchApplications();
        } catch (error) { adminApiError.textContent = error.message; }
    }

    async function fetchApplicationDetails(id) {
        try {
            const result = await makeApiCall(`/admin/applications/${id}`);
            displayResponse(result, `Details for Application #${id}`);
        } catch(error) {
            displayResponse({ error: error.message }, "Error fetching details");
        }
    }

    // --- Student Logic ---
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            const result = await makeApiCall('/applications', { method: 'POST', body: new FormData(uploadForm) });
            displayResponse(result, "Submission Result");
            alert('Application submitted successfully!');
            fetchMyApplications(); // Refresh list
            showStudentTab('list');
        } catch (error) {
            displayResponse({ error: error.message }, "Submission Error");
        }
    });

    function addAspiration() {
        aspirationCount++;
        const container = document.getElementById('aspirations-container');
        const newAspirationDiv = document.createElement('div');
        newAspirationDiv.className = 'card';
        newAspirationDiv.innerHTML = `
            <h4>Nguyện vọng ${aspirationCount}</h4>
            <label for="major_id_${aspirationCount}">Major ID (e.g., 1 for IT, 2 for Business):</label>
            <input type="number" name="aspirations[${aspirationCount - 1}][major_id]" id="major_id_${aspirationCount}" value="1" required>
            <label for="priority_${aspirationCount}">Ưu tiên:</label>
            <input type="number" name="aspirations[${aspirationCount - 1}][priority]" id="priority_${aspirationCount}" value="${aspirationCount}" min="1" required>
        `;
        container.appendChild(newAspirationDiv);
    }
    
    async function fetchMyApplications() {
        try {
            const result = await makeApiCall('/applications');
            const tableBody = document.getElementById('my-applications-table-body');
            tableBody.innerHTML = '';
            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4">You have not submitted any applications.</td></tr>';
                return;
            }
            result.data.forEach(app => {
                 const row = `<tr>
                    <td>${app.id}</td>
                    <td>${app.status}</td>
                    <td>${new Date(app.submitted_at).toLocaleString()}</td>
                    <td><button onclick="fetchMyAppDetails(${app.id})">View Details</button></td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        } catch(error) {
            displayResponse({ error: error.message }, "Error fetching my applications");
        }
    }
    
    async function fetchMyAppDetails(id) {
        try {
            const result = await makeApiCall(`/applications/${id}`);
            displayResponse(result, `Details for Application #${id}`);
        } catch(error) {
            displayResponse({ error: error.message }, "Error fetching details");
        }
    }

    async function fetchNotifications() {
        try {
            const result = await makeApiCall('/notifications');
            const listEl = document.getElementById('notifications-list');
            listEl.innerHTML = '';
            if (result.length === 0) {
                listEl.innerHTML = '<p>No new notifications.</p>';
                return;
            }
            result.forEach(n => {
                const isRead = n.read_at !== null;
                const notifDiv = document.createElement('div');
                notifDiv.className = 'card';
                notifDiv.style.opacity = isRead ? 0.6 : 1;
                notifDiv.innerHTML = `
                    <p>${n.data.message}</p>
                    <small>${new Date(n.created_at).toLocaleString()}</small>
                    ${!isRead ? `<button class="button-secondary" style="width: auto; margin-top: 1em;" onclick="markAsRead('${n.id}')">Mark as Read</button>` : ''}
                `;
                listEl.appendChild(notifDiv);
            });
        } catch(error) {
             displayResponse({ error: error.message }, "Error fetching notifications");
        }
    }

    async function markAsRead(id) {
        try {
            await makeApiCall(`/notifications/${id}/read`, { method: 'PATCH' });
            fetchNotifications(); // Refresh list
        } catch(error) {
            displayResponse({ error: error.message }, "Error marking notification");
        }
    }
    
    // Initializer
    document.addEventListener('DOMContentLoaded', () => {
        showView('loggedOut');
    });

</script>

</body>
</html> 