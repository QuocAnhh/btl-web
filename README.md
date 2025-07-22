# Hệ thống Backend - Tư vấn tuyển sinh

Đây là dự án backend được xây dựng bằng Laravel 11, cung cấp một bộ RESTful API cho hệ thống tư vấn tuyển sinh. Hệ thống được thiết kế để hoạt động với một giao diện frontend duy nhất, nhưng hiển thị các chức năng khác nhau dựa trên vai trò của người dùng (Thí sinh hoặc Quản trị viên).

## Chức năng chính

-   **Gợi ý ngành học:** Cung cấp gợi ý ngành học dựa trên điểm thi hoặc học bạ.
-   **Nộp hồ sơ trực tuyến:** Cho phép thí sinh nộp hồ sơ, bao gồm nhiều nguyện vọng và các tài liệu đính kèm (học bạ, CCCD...).
-   **Quản lý hồ sơ (Thí sinh):** Thí sinh có thể xem lại lịch sử các hồ sơ đã nộp và theo dõi trạng thái.
-   **Quản lý hồ sơ (Admin):** Quản trị viên có thể xem, lọc và cập nhật trạng thái (ví dụ: Chờ xử lý, Đã duyệt, Đã từ chối) cho tất cả hồ sơ.
-   **Hệ thống thông báo:** Tự động gửi thông báo cho thí sinh khi trạng thái hồ sơ của họ được cập nhật.
-   **Dashboard hợp nhất:** Một giao diện duy nhất (`/dashboard`) phục vụ cả thí sinh và quản trị viên.

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
    Lệnh này sẽ tạo tất cả các bảng và điền dữ liệu mẫu (ngành học, người dùng admin/student).
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
    Backend sẽ chạy tại `http://127.0.0.1:8000`. Bạn có thể truy cập trang dashboard kiểm thử tại `http://127.0.0.1:8000/dashboard`.

---

## Tài khoản thử nghiệm

Sau khi chạy `migrate:fresh --seed`, hệ thống sẽ tạo sẵn 2 tài khoản để kiểm thử:

-   **Quản trị viên (Admin):**
    -   Email: `admin@example.com`
    -   Password: `password`
-   **Thí sinh (Student):**
    -   Email: `student@example.com`
    -   Password: `password`

Đăng nhập với các tài khoản này trên trang `/dashboard` để xem các giao diện tương ứng.

---

## Danh sách API Endpoints

URL cơ sở: `http://127.0.0.1:8000/api`

### 1. Xác thực & Người dùng

#### `POST /register`
Đăng ký tài khoản mới cho thí sinh.

#### `POST /login`
Đăng nhập để nhận token xác thực. Trả về `access_token` và thông tin `user` (bao gồm `is_admin`).

#### `POST /logout`
*Yêu cầu xác thực.* Hủy token hiện tại.

#### `GET /user`
*Yêu cầu xác thực.* Lấy thông tin của người dùng đang đăng nhập.

### 2. Gợi ý ngành học

#### `POST /suggestions/by-score`
Gợi ý các ngành học phù hợp dựa trên điểm số của thí sinh (không cần xác thực).

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

### 3. Hồ sơ (Thí sinh)

*(Yêu cầu xác thực)*

#### `POST /applications`
Nộp một hồ sơ xét tuyển mới. Request này phải là `multipart/form-data`.

**Request Body (form-data):**
-   `aspirations[0][major_id]`: (number) ID của ngành
-   `aspirations[0][priority]`: (number) Thứ tự ưu tiên
-   `...`
-   `documents[hoc_ba]`: (file) File học bạ
-   `documents[cccd]`: (file) File CCCD
-   `documents[bang_tot_nghiep]`: (file)

#### `GET /applications`
Lấy danh sách các hồ sơ đã nộp của người dùng đang đăng nhập.

#### `GET /applications/{application}`
Lấy chi tiết một hồ sơ cụ thể mà người dùng sở hữu.

### 4. Quản lý Hồ sơ (Admin)

URL cơ sở: `http://127.0.0.1:8000/api/admin`

*(Yêu cầu xác thực và quyền Admin)*

#### `GET /applications`
Lấy danh sách tất cả hồ sơ trong hệ thống, có phân trang.

**Query Parameters:**
-   `status` (string, optional): Lọc theo trạng thái (`pending`, `processing`, `approved`, `rejected`).
    _Ví dụ: `/api/admin/applications?status=pending`_

#### `GET /applications/{application}`
Lấy chi tiết một hồ sơ bất kỳ trong hệ thống.

#### `PATCH /applications/{application}/status`
Cập nhật trạng thái của một hồ sơ. Khi cập nhật, một thông báo sẽ được gửi đến người dùng.

**Request Body (JSON):**
```json
{
    "status": "approved" // "processing", "approved", hoặc "rejected"
}
```

### 5. Thông báo (Thí sinh)

*(Yêu cầu xác thực)*

#### `GET /notifications`
Lấy danh sách tất cả thông báo của người dùng (chưa đọc và đã đọc).

#### `PATCH /notifications/{notification}/read`
Đánh dấu một thông báo là đã đọc. 