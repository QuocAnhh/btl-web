<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Application Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Nộp Hồ Sơ</h1>
        <p>Form này sẽ gửi yêu cầu POST đến <code>/api/applications</code></p>
        
        <form id="uploadForm" action="/api/applications" method="POST" enctype="multipart/form-data">
            
            <h3 class="mt-4">Nguyện vọng</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nguyện vọng 1</h5>
                    <div class="mb-3">
                        <label for="major1" class="form-label">Major ID (Ngành 1: CNTT, Ngành 2: QTKD)</label>
                        <input type="text" class="form-control" name="aspirations[0][major_id]" id="major1" value="1">
                    </div>
                    <div class="mb-3">
                        <label for="priority1" class="form-label">Priority</label>
                        <input type="text" class="form-control" name="aspirations[0][priority]" id="priority1" value="1">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nguyện vọng 2</h5>
                    <div class="mb-3">
                        <label for="major2" class="form-label">Major ID (Ngành 1: CNTT, Ngành 2: QTKD)</label>
                        <input type="text" class="form-control" name="aspirations[1][major_id]" id="major2" value="2">
                    </div>
                    <div class="mb-3">
                        <label for="priority2" class="form-label">Priority</label>
                        <input type="text" class="form-control" name="aspirations[1][priority]" id="priority2" value="2">
                    </div>
                </div>
            </div>

            <h3 class="mt-4">Tài liệu</h3>
            <div class="mb-3">
                <label for="hoc_ba" class="form-label">Học bạ (Bắt buộc)</label>
                <input class="form-control" type="file" name="documents[hoc_ba]" id="hoc_ba" required>
            </div>
            <div class="mb-3">
                <label for="cccd" class="form-label">CCCD (Bắt buộc)</label>
                <input class="form-control" type="file" name="documents[cccd]" id="cccd" required>
            </div>
            <div class="mb-3">
                <label for="bang_tot_nghiep" class="form-label">Bằng tốt nghiệp (Không bắt buộc)</label>
                <input class="form-control" type="file" name="documents[bang_tot_nghiep]" id="bang_tot_nghiep">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Nộp Hồ Sơ</button>
        </form>

        <hr class="my-5">
        
        <h2>Kết quả</h2>
        <pre><code id="result" class="json" style="white-space: pre-wrap; word-break: break-all;"></code></pre>

    </div>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const resultElement = document.getElementById('result');
            resultElement.textContent = 'Đang gửi...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                resultElement.textContent = JSON.stringify(data, null, 2);

            } catch (error) {
                resultElement.textContent = 'Đã có lỗi xảy ra: ' + error.message;
            }
        });
    </script>
</body>
</html> 