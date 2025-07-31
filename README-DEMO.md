# Demo Trang Quản Trị - HUMG

## 📋 Mô tả

Đây là phiên bản demo của trang quản trị hệ thống tuyển sinh HUMG, được tạo để bạn có thể xem giao diện và chức năng mà không cần backend Laravel chạy.

## 🚀 Cách sử dụng

### 1. Mở trang demo

- Mở file `trangadmin-demo.html` trong trình duyệt
- Hoặc click vào link: `./trangadmin-demo.html`

### 2. Khám phá các chức năng

#### 📊 **Trang Tổng quan**

- Xem thống kê tổng quan về hồ sơ
- Danh sách hồ sơ gần đây
- Các chỉ số quan trọng

#### 📁 **Quản lý hồ sơ**

- Xem danh sách tất cả hồ sơ
- Lọc theo trạng thái (Chờ xử lý, Đang xử lý, Đã duyệt, Từ chối)
- Tìm kiếm theo tên thí sinh hoặc email
- Xem chi tiết hồ sơ trong modal
- Duyệt hoặc từ chối hồ sơ

#### 👥 **Quản lý người dùng**

- Xem thống kê người dùng
- Demo giao diện quản lý

#### 🔔 **Thông báo**

- Xem danh sách thông báo
- Demo hệ thống thông báo

#### ⚙️ **Cài đặt**

- Xem các tùy chọn cấu hình hệ thống
- Demo giao diện cài đặt

## 📊 Dữ liệu Demo

### Hồ sơ mẫu:

1. **Nguyễn Văn An** - Chờ xử lý

   - Nguyện vọng: Công nghệ thông tin, Khoa học máy tính
   - Tài liệu: Học bạ, CCCD

2. **Trần Thị Bình** - Đã duyệt

   - Nguyện vọng: Quản trị kinh doanh
   - Tài liệu: Học bạ, CCCD, Bằng tốt nghiệp

3. **Lê Văn Cường** - Từ chối

   - Nguyện vọng: Công nghệ thông tin, Kỹ thuật điện
   - Tài liệu: Học bạ

4. **Phạm Thị Dung** - Đang xử lý

   - Nguyện vọng: Công nghệ thông tin, Truyền thông và mạng máy tính, Hệ thống thông tin
   - Tài liệu: Học bạ, CCCD

5. **Hoàng Văn Em** - Chờ xử lý
   - Nguyện vọng: Khoa học máy tính
   - Tài liệu: Học bạ, CCCD

## 🎯 Chức năng Demo

### ✅ **Đã hoạt động:**

- Xem danh sách hồ sơ
- Lọc và tìm kiếm
- Xem chi tiết hồ sơ
- Duyệt/từ chối hồ sơ (cập nhật dữ liệu demo)
- Điều hướng giữa các trang
- Responsive design

### ⚠️ **Chỉ là demo:**

- Dữ liệu không được lưu trữ
- Không kết nối backend thật
- Chỉ cập nhật trong phiên làm việc hiện tại

## 🎨 Giao diện

### **Thiết kế hiện đại:**

- Gradient màu xanh HUMG
- Card layout với shadow
- Status badges màu sắc phân biệt
- Modal popup cho chi tiết
- Responsive cho mobile/tablet

### **Màu sắc:**

- **Chờ xử lý:** Vàng (#fef3c7)
- **Đang xử lý:** Xanh dương (#dbeafe)
- **Đã duyệt:** Xanh lá (#d1fae5)
- **Từ chối:** Đỏ (#fee2e2)

## 📱 Responsive

Trang demo được thiết kế responsive:

- **Desktop:** Layout 2 cột (sidebar + content)
- **Tablet:** Layout 1 cột, sidebar ở dưới
- **Mobile:** Menu thu gọn, bảng scroll ngang

## 🔧 Cách chạy

1. **Mở trực tiếp file:**

   ```
   trangadmin-demo.html
   ```

2. **Hoặc qua server local:**

   ```bash
   # Nếu có Python
   python -m http.server 8000

   # Nếu có Node.js
   npx serve .
   ```

3. **Truy cập:**
   ```
   http://localhost:8000/trangadmin-demo.html
   ```

## 📝 Ghi chú

- Đây chỉ là demo giao diện, không có backend thật
- Dữ liệu sẽ reset khi refresh trang
- Các chức năng chỉ mô phỏng, không lưu trữ
- Để test với backend thật, cần chạy Laravel server

## 🎯 Mục đích

Demo này giúp bạn:

- Xem giao diện và UX của trang admin
- Hiểu luồng làm việc của admin
- Test responsive design
- Đánh giá tính năng trước khi tích hợp backend

---

**Lưu ý:** Đây là phiên bản demo, khi backend Laravel chạy, bạn có thể sử dụng trang admin thật tại `trangadmin.html`
