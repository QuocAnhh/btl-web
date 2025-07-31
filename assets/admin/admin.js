// JavaScript cho trang Admin

// Biến toàn cục
let nguoiDungHienTai = null;
let danhSachHoSo = [];
let trangHienTai = "tongQuan";

// Khởi tạo trang
document.addEventListener("DOMContentLoaded", function () {
  kiemTraDangNhap();
  taiDuLieuTongQuan();
});

// Kiểm tra đăng nhập
function kiemTraDangNhap() {
  const token = localStorage.getItem("auth_token");
  const user = localStorage.getItem("user");

  if (!token || !user) {
    window.location.href = "./trangchu.html";
    return;
  }

  nguoiDungHienTai = JSON.parse(user);

  // Kiểm tra quyền admin
  if (!nguoiDungHienTai.is_admin) {
    alert("Bạn không có quyền truy cập trang quản trị!");
    window.location.href = "./trangchu.html";
    return;
  }

  // Hiển thị thông tin người dùng
  document.getElementById("userName").textContent = nguoiDungHienTai.name;
  document.getElementById("userAvatar").innerHTML = nguoiDungHienTai.name
    .charAt(0)
    .toUpperCase();
}

// Đăng xuất
async function dangXuat() {
  const token = localStorage.getItem("auth_token");

  if (token) {
    try {
      await fetch("http://127.0.0.1:8000/api/logout", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
    } catch (error) {
      console.log("Lỗi đăng xuất:", error);
    }
  }

  localStorage.removeItem("auth_token");
  localStorage.removeItem("user");
  window.location.href = "./trangchu.html";
}

// Hiển thị trang
function hienThiTrang(trang) {
  // Ẩn tất cả trang
  const cacTrang = document.querySelectorAll(".page-content");
  cacTrang.forEach((trang) => (trang.style.display = "none"));

  // Hiển thị trang được chọn
  document.getElementById(trang).style.display = "block";

  // Cập nhật menu active
  const cacMenu = document.querySelectorAll(".menu-link");
  cacMenu.forEach((menu) => menu.classList.remove("active"));
  event.target.classList.add("active");

  trangHienTai = trang;

  // Tải dữ liệu tương ứng
  if (trang === "tongQuan") {
    taiDuLieuTongQuan();
  } else if (trang === "hoSo") {
    taiDanhSachHoSo();
  }
}

// Tải dữ liệu tổng quan
async function taiDuLieuTongQuan() {
  try {
    const token = localStorage.getItem("auth_token");
    const response = await fetch(
      "http://127.0.0.1:8000/api/admin/applications",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.ok) {
      const data = await response.json();
      danhSachHoSo = data.data || [];

      // Tính toán thống kê
      const tongHoSo = danhSachHoSo.length;
      const hoSoCho = danhSachHoSo.filter(
        (hs) => hs.status === "pending"
      ).length;
      const hoSoDuyet = danhSachHoSo.filter(
        (hs) => hs.status === "approved"
      ).length;
      const hoSoTuChoi = danhSachHoSo.filter(
        (hs) => hs.status === "rejected"
      ).length;

      // Cập nhật thống kê
      document.getElementById("tongHoSo").textContent = tongHoSo;
      document.getElementById("hoSoCho").textContent = hoSoCho;
      document.getElementById("hoSoDuyet").textContent = hoSoDuyet;
      document.getElementById("hoSoTuChoi").textContent = hoSoTuChoi;

      // Hiển thị hồ sơ gần đây
      hienThiHoSoGanDay();
    }
  } catch (error) {
    console.error("Lỗi tải dữ liệu:", error);
  }
}

// Hiển thị hồ sơ gần đây
function hienThiHoSoGanDay() {
  const tableBody = document.getElementById("recentTableBody");
  const hoSoGanDay = danhSachHoSo.slice(0, 5); // Lấy 5 hồ sơ gần nhất

  tableBody.innerHTML = hoSoGanDay
    .map(
      (hoSo) => `
          <tr>
              <td>${hoSo.user.name}</td>
              <td>${new Date(hoSo.submitted_at).toLocaleDateString(
                "vi-VN"
              )}</td>
              <td><span class="status-badge status-${
                hoSo.status
              }">${layTenTrangThai(hoSo.status)}</span></td>
              <td>
                  <button class="btn btn-primary" onclick="xemChiTiet(${
                    hoSo.id
                  })">
                      <i class="fas fa-eye"></i> Xem
                  </button>
              </td>
          </tr>
      `
    )
    .join("");
}

// Tải danh sách hồ sơ
async function taiDanhSachHoSo() {
  try {
    const token = localStorage.getItem("auth_token");
    const response = await fetch(
      "http://127.0.0.1:8000/api/admin/applications",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.ok) {
      const data = await response.json();
      danhSachHoSo = data.data || [];
      hienThiDanhSachHoSo();
    }
  } catch (error) {
    console.error("Lỗi tải danh sách hồ sơ:", error);
  }
}

// Hiển thị danh sách hồ sơ
function hienThiDanhSachHoSo(hoSoLoc = null) {
  const tableBody = document.getElementById("applicationsTableBody");
  const danhSachHienThi = hoSoLoc || danhSachHoSo;

  tableBody.innerHTML = danhSachHienThi
    .map(
      (hoSo) => `
          <tr>
              <td>${hoSo.user.name}</td>
              <td>${hoSo.user.email}</td>
              <td>${new Date(hoSo.submitted_at).toLocaleDateString(
                "vi-VN"
              )}</td>
              <td>${
                hoSo.aspirations ? hoSo.aspirations.length : 0
              } nguyện vọng</td>
              <td><span class="status-badge status-${
                hoSo.status
              }">${layTenTrangThai(hoSo.status)}</span></td>
              <td>
                  <button class="btn btn-primary" onclick="xemChiTiet(${
                    hoSo.id
                  })">
                      <i class="fas fa-eye"></i> Xem
                  </button>
                  <button class="btn btn-success" onclick="capNhatTrangThai(${
                    hoSo.id
                  }, 'approved')">
                      <i class="fas fa-check"></i> Duyệt
                  </button>
                  <button class="btn btn-danger" onclick="capNhatTrangThai(${
                    hoSo.id
                  }, 'rejected')">
                      <i class="fas fa-times"></i> Từ chối
                  </button>
              </td>
          </tr>
      `
    )
    .join("");
}

// Lọc hồ sơ
function locHoSo() {
  const trangThai = document.getElementById("filterTrangThai").value;
  const tuKhoa = document.getElementById("searchInput").value.toLowerCase();

  let hoSoLoc = danhSachHoSo;

  // Lọc theo trạng thái
  if (trangThai) {
    hoSoLoc = hoSoLoc.filter((hs) => hs.status === trangThai);
  }

  // Lọc theo từ khóa
  if (tuKhoa) {
    hoSoLoc = hoSoLoc.filter(
      (hs) =>
        hs.user.name.toLowerCase().includes(tuKhoa) ||
        hs.user.email.toLowerCase().includes(tuKhoa)
    );
  }

  hienThiDanhSachHoSo(hoSoLoc);
}

// Tìm kiếm hồ sơ
function timKiemHoSo() {
  locHoSo();
}

// Lấy tên trạng thái
function layTenTrangThai(status) {
  const trangThai = {
    pending: "Chờ xử lý",
    processing: "Đang xử lý",
    approved: "Đã duyệt",
    rejected: "Từ chối",
  };
  return trangThai[status] || status;
}

// Xem chi tiết hồ sơ
async function xemChiTiet(hoSoId) {
  try {
    const token = localStorage.getItem("auth_token");
    const response = await fetch(
      `http://127.0.0.1:8000/api/admin/applications/${hoSoId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    if (response.ok) {
      const hoSo = await response.json();
      hienThiModalChiTiet(hoSo);
    }
  } catch (error) {
    console.error("Lỗi tải chi tiết hồ sơ:", error);
  }
}

// Hiển thị modal chi tiết
function hienThiModalChiTiet(hoSo) {
  const modal = document.getElementById("modalChiTiet");
  const content = document.getElementById("modalContent");

  content.innerHTML = `
          <div style="margin-bottom: 20px;">
              <h4>Thông tin thí sinh</h4>
              <p><strong>Tên:</strong> ${hoSo.user.name}</p>
              <p><strong>Email:</strong> ${hoSo.user.email}</p>
              <p><strong>Ngày nộp:</strong> ${new Date(
                hoSo.submitted_at
              ).toLocaleDateString("vi-VN")}</p>
              <p><strong>Trạng thái:</strong> <span class="status-badge status-${
                hoSo.status
              }">${layTenTrangThai(hoSo.status)}</span></p>
          </div>

          <div style="margin-bottom: 20px;">
              <h4>Nguyện vọng</h4>
              ${
                hoSo.aspirations
                  ? hoSo.aspirations
                      .map(
                        (nv, index) => `
                  <div style="border: 1px solid #e5e7eb; padding: 10px; margin: 5px 0; border-radius: 6px;">
                      <p><strong>Nguyện vọng ${index + 1}:</strong> ${
                          nv.major ? nv.major.name : "N/A"
                        }</p>
                      <p><strong>Thứ tự ưu tiên:</strong> ${nv.priority}</p>
                  </div>
              `
                      )
                      .join("")
                  : "<p>Không có nguyện vọng</p>"
              }
          </div>

          <div style="margin-bottom: 20px;">
              <h4>Tài liệu đính kèm</h4>
              ${
                hoSo.documents
                  ? hoSo.documents
                      .map(
                        (doc) => `
                  <div style="border: 1px solid #e5e7eb; padding: 10px; margin: 5px 0; border-radius: 6px;">
                      <p><strong>Loại tài liệu:</strong> ${doc.document_type}</p>
                      <p><strong>Tên file:</strong> ${doc.original_filename}</p>
                  </div>
              `
                      )
                      .join("")
                  : "<p>Không có tài liệu</p>"
              }
          </div>

          <div style="text-align: center; margin-top: 20px;">
              <button class="btn btn-success" onclick="capNhatTrangThai(${
                hoSo.id
              }, 'approved')">
                  <i class="fas fa-check"></i> Duyệt hồ sơ
              </button>
              <button class="btn btn-danger" onclick="capNhatTrangThai(${
                hoSo.id
              }, 'rejected')">
                  <i class="fas fa-times"></i> Từ chối hồ sơ
              </button>
          </div>
      `;

  modal.style.display = "block";
}

// Cập nhật trạng thái hồ sơ
async function capNhatTrangThai(hoSoId, trangThaiMoi) {
  if (
    !confirm(
      `Bạn có chắc muốn ${
        trangThaiMoi === "approved" ? "duyệt" : "từ chối"
      } hồ sơ này?`
    )
  ) {
    return;
  }

  try {
    const token = localStorage.getItem("auth_token");
    const response = await fetch(
      `http://127.0.0.1:8000/api/admin/applications/${hoSoId}/status`,
      {
        method: "PATCH",
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          status: trangThaiMoi,
        }),
      }
    );

    if (response.ok) {
      alert("Cập nhật trạng thái thành công!");
      dongModal();

      // Tải lại dữ liệu
      if (trangHienTai === "tongQuan") {
        taiDuLieuTongQuan();
      } else if (trangHienTai === "hoSo") {
        taiDanhSachHoSo();
      }
    } else {
      alert("Có lỗi xảy ra khi cập nhật trạng thái!");
    }
  } catch (error) {
    console.error("Lỗi cập nhật trạng thái:", error);
    alert("Có lỗi xảy ra khi cập nhật trạng thái!");
  }
}

// Đóng modal
function dongModal() {
  document.getElementById("modalChiTiet").style.display = "none";
}

// Đóng modal khi click bên ngoài
window.onclick = function (event) {
  const modal = document.getElementById("modalChiTiet");
  if (event.target === modal) {
    dongModal();
  }
};
