# Hệ thống Backend - Tư vấn tuyển sinh

Đây là dự án backend được xây dựng bằng Laravel 11, cung cấp một bộ RESTful API cho hệ thống tư vấn tuyển sinh.

Hệ thống bao gồm các chức năng chính:
- Gợi ý ngành học dựa trên điểm số.
- Nộp hồ sơ xét tuyển trực tuyến (bao gồm upload tài liệu).
- Quản lý nguyện vọng của thí sinh.
- Trang quản trị để quản lý hồ sơ, ngành học, và người dùng.

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- MySQL
- Một web server (ví dụ: Nginx, Apache trong XAMPP/LAMP)

## Hướng dẫn cài đặt và khởi chạy

1.  **Clone dự án**
    ```bash
    git clone <your-repository-url>
    cd backend-web
    ```

2.  **Cài đặt các gói phụ thuộc**
    ```bash
    composer install
    ```

3.  **Cấu hình môi trường**
    Sao chép file `.env.example` thành `.env`.
    ```bash
    cp .env.example .env
    ```
    Mở file `.env` và cấu hình các thông tin kết nối cơ sở dữ liệu (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=backend_web_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Sinh khóa ứng dụng**
    ```bash
    php artisan key:generate
    ```

5.  **Tạo cấu trúc database và dữ liệu mẫu**
    Lệnh này sẽ tạo tất cả các bảng và điền dữ liệu mẫu (ngành học, người dùng admin...).
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Liên kết thư mục lưu trữ**
    Để các file upload có thể được truy cập từ web.
    ```bash
    php artisan storage:link
    ```

7.  **Khởi chạy server**
    ```bash
    php artisan serve
    ```
    Backend sẽ chạy tại `http://127.0.0.1:8000`.

---

## Hướng dẫn kết nối cho Frontend

Backend và Frontend là hai hệ thống riêng biệt, giao tiếp với nhau qua các API endpoint.

### Vấn đề CORS

Khi Frontend chạy ở một domain khác (ví dụ `localhost:3000`) và gọi API ở `localhost:8000`, trình duyệt sẽ chặn request do chính sách CORS. Để giải quyết, backend đã được cấu hình để cho phép các request này. Bạn cần đảm bảo file `config/cors.php` có cấu hình phù hợp, ví dụ cho phép tất cả:
```php
// config/cors.php
'allowed_origins' => ['*'],
```

### Quy trình xác thực (Authentication Flow)

*(Lưu ý: Hiện tại middleware xác thực đang được tạm tắt để kiểm tra. Khi được bật lại, đây là quy trình chuẩn.)*

Hệ thống sử dụng **Token-based authentication (Laravel Sanctum)**.

1.  **Đăng nhập:** Frontend gửi request `POST` đến `/api/login` với `email` và `password`.
2.  **Nhận Token:** Nếu thành công, backend sẽ trả về một `access_token`.
3.  **Lưu Token:** Frontend phải lưu lại token này (ví dụ trong Local Storage).
4.  **Gửi Token với mỗi Request:** Với tất cả các request cần xác thực sau đó, Frontend phải đính kèm token vào header `Authorization`.
    ```
    Authorization: Bearer <your_saved_token>
    ```

---

## Danh sách API Endpoints

URL cơ sở: `http://127.0.0.1:8000/api`

### 1. Gợi ý ngành học

#### `POST /suggestions/by-score`
Gợi ý các ngành học phù hợp dựa trên điểm số của thí sinh.

**Request Body (JSON):**
```json
{
    "admission_method": "exam_score", // "exam_score" hoặc "transcript_score"
    "scores": [
        {"subject_name": "Toán", "score": 8.5},
        {"subject_name": "Lý", "score": 8.0},
        {"subject_name": "Hóa", "score": 7.5}
    ]
}
```

**Response thành công (200 OK):**
```json
{
    "message": "Suggested majors based on your scores.",
    "data": [
        {
            "id": 1,
            "name": "Công nghệ thông tin",
            "code": "7480201",
            "description": "Ngành học về...",
            "matched_criterion": "Xét tuyển khối A00 - Điểm thi THPT 2024"
        }
    ]
}
```

### 2. Nộp và xem hồ sơ (User)

*(Yêu cầu xác thực)*

#### `POST /applications`
Nộp một hồ sơ xét tuyển mới. Request này phải là `multipart/form-data`.

**Request Body (form-data):**
- `aspirations[0][major_id]`: (number) ID của ngành
- `aspirations[0][priority]`: (number) Thứ tự ưu tiên
- `aspirations[1][major_id]`: ...
- `aspirations[1][priority]`: ...
- `documents[hoc_ba]`: (file) File học bạ
- `documents[cccd]`: (file) File CCCD
- `documents[bang_tot_nghiep]`: (file, optional)

**Response thành công (201 Created):**
Trả về chi tiết hồ sơ vừa tạo, bao gồm danh sách nguyện vọng và tài liệu.

#### `GET /applications`
Lấy danh sách các hồ sơ đã nộp của người dùng đang đăng nhập.

#### `GET /applications/{id}`
Lấy chi tiết một hồ sơ cụ thể.

---

### 3. Quản lý hồ sơ (Admin)

URL cơ sở: `http://127.0.0.1:8000/api/admin`

*(Yêu cầu xác thực và quyền Admin)*

#### `GET /applications`
Lấy danh sách tất cả hồ sơ trong hệ thống. Có thể lọc theo trạng thái.

**Query Parameters:**
- `status` (string, optional): Lọc theo trạng thái (`pending`, `processing`, `approved`, `rejected`).
  _Ví dụ: `/api/admin/applications?status=pending`_

**Response thành công (200 OK):**
Trả về một đối tượng có phân trang (pagination).
```json
{
    "current_page": 1,
    "data": [
        // ... danh sách hồ sơ ...
    ],
    // ... các thông tin phân trang khác ...
}
```

#### `PATCH /applications/{id}/status`
Cập nhật trạng thái của một hồ sơ.

**Request Body (JSON):**
```json
{
    "status": "approved" // "processing", "approved", hoặc "rejected"
}
```
**Response thành công (200 OK):**
Trả về chi tiết hồ sơ đã được cập nhật. 