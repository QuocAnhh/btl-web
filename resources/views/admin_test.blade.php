<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Test Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            white-space: pre-wrap;
            word-break: break-all;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Test Interface</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">1. Lấy danh sách hồ sơ</h5>
                <p>Gọi đến <code>GET /api/admin/applications</code></p>
                <button id="fetchApplications" class="btn btn-primary">Lấy danh sách hồ sơ</button>
            </div>
        </div>

        <hr>

        <h2>Danh sách hồ sơ</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người nộp</th>
                    <th>Email</th>
                    <th>Ngày nộp</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="applicationsTableBody">
                <!-- Data will be inserted here by JavaScript -->
            </tbody>
        </table>

        <hr class="my-5">

        <h2>Kết quả chi tiết / Lỗi</h2>
        <pre><code id="result"></code></pre>
    </div>

    <script>
        const applicationsTableBody = document.getElementById('applicationsTableBody');
        const resultElement = document.getElementById('result');
        const fetchApplicationsBtn = document.getElementById('fetchApplications');

        // Function to fetch all applications
        async function fetchApplications() {
            resultElement.textContent = 'Đang tải...';
            applicationsTableBody.innerHTML = '';
            try {
                const response = await fetch('/api/admin/applications');
                const data = await response.json();

                if (data.data && data.data.length > 0) {
                    data.data.forEach(app => {
                        const row = `
                            <tr>
                                <td>${app.id}</td>
                                <td>${app.user.name}</td>
                                <td>${app.user.email}</td>
                                <td>${new Date(app.submitted_at).toLocaleString()}</td>
                                <td><span class="badge bg-secondary">${app.status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="fetchDetails(${app.id})">Xem</button>
                                    <select class="form-select-sm" onchange="updateStatus(${app.id}, this.value)">
                                        <option value="">Đổi trạng thái</option>
                                        <option value="processing">Processing</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </td>
                            </tr>
                        `;
                        applicationsTableBody.innerHTML += row;
                    });
                    resultElement.textContent = 'Tải danh sách thành công!';
                } else {
                    resultElement.textContent = 'Không có hồ sơ nào.';
                }
            } catch (error) {
                resultElement.textContent = 'Lỗi: ' + error.message;
            }
        }

        // Function to fetch application details
        async function fetchDetails(id) {
            resultElement.textContent = `Đang tải chi tiết hồ sơ ID: ${id}...`;
            try {
                const response = await fetch(`/api/admin/applications/${id}`);
                const data = await response.json();
                resultElement.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                resultElement.textContent = 'Lỗi: ' + error.message;
            }
        }

        // Function to update status
        async function updateStatus(id, newStatus) {
            if (!newStatus) return;
            resultElement.textContent = `Đang cập nhật trạng thái hồ sơ ID: ${id}...`;
            try {
                const response = await fetch(`/api/admin/applications/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                const data = await response.json();
                resultElement.textContent = 'Cập nhật thành công! Vui lòng "Lấy danh sách hồ sơ" để xem thay đổi.\n\n' + JSON.stringify(data, null, 2);
            } catch (error) {
                resultElement.textContent = 'Lỗi: ' + error.message;
            }
        }
        
        fetchApplicationsBtn.addEventListener('click', fetchApplications);
        
        // Fetch on page load
        fetchApplications();
    </script>
</body>
</html> 