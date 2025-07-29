// bấm img logo reload
const header_img = document.querySelector('.header-img');
const header_img_icon = header_img.querySelector('img');
header_img_icon.addEventListener('click', function() {
    location.reload();
});
/* bars va menu-dow */
const bt_bars = document.querySelector('.header-bars');
const icon_bars = bt_bars.querySelector('i');
const header_list_down = document.querySelector('.header-list-down');

let menuIsOpen = false; // Biến kiểm tra trạng thái của menu

const click_bt_bars = () => {
    if (menuIsOpen) {
        closeMenu();
    } else {
        openMenu();
    }
};

function openMenu() {
    icon_bars.className = 'fa-solid fa-xmark fa-2xl';
    header_list_down.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    menuIsOpen = true;

    window.addEventListener('resize', handleResize);
}

function closeMenu() {
    icon_bars.className = 'fa-solid fa-bars fa-2xl';
    header_list_down.style.display = 'none';
    document.body.style.overflow = 'auto';
    menuIsOpen = false;

    window.removeEventListener('resize', handleResize);
}

function handleResize() {
    if (window.innerWidth > 980 && menuIsOpen) {
        closeMenu();
    }
}

bt_bars.addEventListener('click', click_bt_bars);
// search

document.querySelector('.header-search__icon i').addEventListener('click', function () {
    const searchQuery = `utc ` + document.querySelector('.header-search__input').value ;
    if (searchQuery) {
        const searchURL =`https://www.google.com/search?q=${encodeURIComponent(searchQuery)}`;
        window.open(searchURL, '_blank'); // _blank dùng để tạo tab mới
    }
});
/* form gợi ý ngành học */
const suggestionForm = document.querySelector('.suggestion-form');
const styleLabelInput = {
    top: '43px',
    color: '#999',
    transition: '0.25s ease',
    left: '21px',
    backgroundColor: '#ffff'
};
const styleLabelInputed = {
    top: '0%',
    color: '#00377a',
    transition: '0.25s ease',
    left: '21px',
    backgroundColor: '#e8f0fe'
};
const styleInput = {
    borderColor: 'black',
    color: 'black',
    backgroundColor: '#ffff',
};
const styleInputed = {
    borderColor: '#00377a',
    color: 'black',
    backgroundColor: '#e8f0fe',
};

const afterInputedData = (inputItem, labelItem) => {
    Object.assign(labelItem.style, styleLabelInputed);
    Object.assign(inputItem.style, styleInputed);
};
const beforeInputedData = (inputItem, labelItem) => {
    Object.assign(labelItem.style, styleLabelInput);
    Object.assign(inputItem.style, styleInput);
};

/* Xử lý form gợi ý ngành học */
const notifications = document.querySelector('.list-notification');
const notification_success = notifications.querySelector('.success-notification');
const notification_error = notifications.querySelector('.error-notification');

// buttion Circle Up
const btn_circle_up = document.querySelector(".btn-top");
// ẩn hiện nút 
window.onscroll = function() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        btn_circle_up.style.display = "block";
    } else {
        btn_circle_up.style.display = "none";
    }
};
  
// tra ve vi tri dau trang
function scrollToTop() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
}
// lắng nghe sự kiện click
btn_circle_up.addEventListener("click", scrollToTop);
  

// Cuộn đến form gợi ý ngành học
function cuonDenViTriForm() {
    const suggestionSection = document.querySelector('.suggestion-section');
    if (suggestionSection) {
        suggestionSection.scrollIntoView({ behavior: "smooth" });
    }
}

// Chức năng gợi ý ngành học
async function getMajorSuggestions() {
    console.log('=== BẮT ĐẦU TÌM KIẾM NGÀNH HỌC ===');
    
    const admissionMethod = document.getElementById('admission-method').value;
    const subjectNames = document.querySelectorAll('.subject-name');
    const subjectScores = document.querySelectorAll('.subject-score');
    
    console.log('Phương thức xét tuyển:', admissionMethod);
    console.log('Số lượng môn học:', subjectNames.length);
    
    // Thu thập dữ liệu điểm
    const scores = [];
    for (let i = 0; i < subjectNames.length; i++) {
        const name = subjectNames[i].value.trim();
        const score = parseFloat(subjectScores[i].value);
        
        console.log(`Môn ${i + 1}:`, name, 'Điểm:', score);
        
        if (name && !isNaN(score) && score >= 0 && score <= 10) {
            scores.push({
                subject_name: name,
                score: score
            });
        }
    }
    
    console.log('Dữ liệu điểm đã thu thập:', scores);
    
    // Kiểm tra dữ liệu đầu vào
    if (scores.length === 0) {
        console.log('LỖI: Không có dữ liệu điểm hợp lệ');
        showError('Vui lòng nhập ít nhất một môn học và điểm số hợp lệ (0-10)');
        return;
    }
    
    // Hiển thị loading
    const resultsContainer = document.getElementById('results-container');
    const suggestionResults = document.getElementById('suggestion-results');
    
    console.log('Hiển thị loading...');
    
    // Hiển thị kết quả và scroll đến đó
    suggestionResults.style.display = 'block';
    resultsContainer.innerHTML = '<div class="loading">Đang tìm kiếm ngành học phù hợp...</div>';
    
    // Smooth scroll đến kết quả
    setTimeout(() => {
        suggestionResults.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }, 100);
    
    try {
        console.log('Gửi request đến API...');
        console.log('Request data:', {
            admission_method: admissionMethod,
            scores: scores
        });
        
        const response = await fetch('http://127.0.0.1:8000/api/suggestions/by-score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                admission_method: admissionMethod,
                scores: scores
            })
        });
        
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.log('Error response:', errorText);
            throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        // Kiểm tra cấu trúc response từ backend
        if (data.data && Array.isArray(data.data) && data.data.length > 0) {
            console.log('Hiển thị kết quả thành công');
            displaySuggestions(data.data);
            // Scroll lại sau khi có kết quả
            setTimeout(() => {
                suggestionResults.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
            }, 200);
        } else {
            console.log('Không có kết quả phù hợp');
            showError('Không tìm thấy ngành học phù hợp với điểm số của bạn. Vui lòng thử lại với điểm số khác.');
        }
        
    } catch (error) {
        console.error('LỖI CHI TIẾT:', error);
        
        // Nếu backend không chạy, hiển thị kết quả demo
        if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
            console.log('Backend không chạy, hiển thị kết quả demo');
            showDemoResults(scores);
        } else {
            showError(`Có lỗi xảy ra khi tìm kiếm ngành học: ${error.message}. Vui lòng thử lại sau.`);
        }
    }
}

function displaySuggestions(majors) {
    const resultsContainer = document.getElementById('results-container');
    const suggestionResults = document.getElementById('suggestion-results');
    
    let html = '';
    majors.forEach((major, index) => {
        html += `
            <div class="major-suggestion">
                <div class="major-header">
                    <div class="major-name">${major.name || 'Ngành học'}</div>
                </div>
                <div class="major-code">Mã ngành: ${major.code || 'N/A'}</div>
                <div class="major-description">${major.description || 'Mô tả ngành học sẽ được hiển thị ở đây.'}</div>
                ${major.matched_criterion ? `<div class="matched-criterion">Tiêu chí phù hợp: ${major.matched_criterion}</div>` : ''}
                <div class="major-actions">
                    <button class="major-detail-btn" onclick="showMajorDetail('${major.id || ''}', '${major.name || ''}')">
                        <i class="fa-solid fa-info-circle"></i>
                        Chi tiết
                    </button>
                    <button class="major-apply-btn" onclick="applyForMajor('${major.id || ''}', '${major.name || ''}')">
                        <i class="fa-solid fa-paper-plane"></i>
                        Đăng ký
                    </button>
                </div>
            </div>
        `;
    });
    
    resultsContainer.innerHTML = html;
    
    // Thêm class highlight và đảm bảo hiển thị
    suggestionResults.classList.add('show');
    suggestionResults.style.display = 'block';
    
    // Thêm thông báo thành công
    console.log(`Đã tìm thấy ${majors.length} ngành học phù hợp!`);
}

function showError(message) {
    const resultsContainer = document.getElementById('results-container');
    const suggestionResults = document.getElementById('suggestion-results');
    
    resultsContainer.innerHTML = `
        <div class="error-message">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Đảm bảo hiển thị kết quả lỗi
    suggestionResults.style.display = 'block';
    suggestionResults.classList.add('show');
}

// Chức năng hiển thị chi tiết ngành học
function showMajorDetail(majorId, majorName) {
    alert(`Chi tiết ngành học: ${majorName}\n\nChức năng này sẽ được phát triển thêm.`);
}

// Chức năng đăng ký ngành học
function applyForMajor(majorId, majorName) {
    alert(`Đăng ký ngành học: ${majorName}\n\nChức năng này sẽ được phát triển thêm.`);
}

// Thêm validation cho input điểm
document.addEventListener('DOMContentLoaded', function() {
    const scoreInputs = document.querySelectorAll('.subject-score');
    
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0) {
                this.value = 0;
            } else if (value > 10) {
                this.value = 10;
            }
        });
    });
});

// Chức năng hiển thị kết quả demo khi backend không chạy
function showDemoResults(scores) {
    const resultsContainer = document.getElementById('results-container');
    const suggestionResults = document.getElementById('suggestion-results');
    
    // Tạo dữ liệu demo dựa trên điểm số
    const demoMajors = generateDemoMajors(scores);
    
    let html = `
        <div class="demo-notice">
            <i class="fa-solid fa-info-circle"></i>
            <strong>Chế độ Demo:</strong> Backend chưa chạy, đây là kết quả mẫu
        </div>
    `;
    
    demoMajors.forEach((major, index) => {
        html += `
            <div class="major-suggestion">
                <div class="major-header">
                    <div class="major-name">${major.name}</div>
                </div>
                <div class="major-code">Mã ngành: ${major.code}</div>
                <div class="major-description">${major.description}</div>
                <div class="matched-criterion">Tiêu chí phù hợp: ${major.matched_criterion}</div>
                <div class="major-actions">
                    <button class="major-detail-btn" onclick="showMajorDetail('${major.id}', '${major.name}')">
                        <i class="fa-solid fa-info-circle"></i>
                        Chi tiết
                    </button>
                    <button class="major-apply-btn" onclick="applyForMajor('${major.id}', '${major.name}')">
                        <i class="fa-solid fa-paper-plane"></i>
                        Đăng ký
                    </button>
                </div>
            </div>
        `;
    });
    
    resultsContainer.innerHTML = html;
    suggestionResults.classList.add('show');
    suggestionResults.style.display = 'block';
    
    console.log('Hiển thị kết quả demo thành công');
}

// Tạo dữ liệu demo dựa trên điểm số
function generateDemoMajors(scores) {
    const demoMajors = [
        {
            id: 1,
            name: 'Công nghệ thông tin',
            code: '7480201',
            description: 'Ngành học về phát triển phần mềm, hệ thống thông tin, và mạng máy tính.',
            matched_criterion: 'Xét tuyển khối A00 - Điểm thi THPT 2024'
        },
        {
            id: 2,
            name: 'Quản trị kinh doanh',
            code: '7340101',
            description: 'Ngành học về quản lý hoạt động kinh doanh, marketing, và nhân sự.',
            matched_criterion: 'Xét tuyển khối A01 - Điểm thi THPT 2024'
        }
    ];
    
    // Lọc và sắp xếp dựa trên điểm số thực tế từ backend
    const filteredMajors = demoMajors.filter(major => {
        // Lấy điểm số từ input
        const mathScore = scores.find(s => s.subject_name.toLowerCase().includes('toán'))?.score || 0;
        const physicsScore = scores.find(s => s.subject_name.toLowerCase().includes('lý'))?.score || 0;
        const chemistryScore = scores.find(s => s.subject_name.toLowerCase().includes('hóa'))?.score || 0;
        const englishScore = scores.find(s => s.subject_name.toLowerCase().includes('anh'))?.score || 0;
        
        // Kiểm tra điều kiện dựa trên tiêu chí thực tế từ backend
        if (major.name.includes('Công nghệ thông tin')) {
            // Tiêu chí A00: Toán >= 8.0, Lý >= 7.5, Hóa >= 7.5
            return mathScore >= 8.0 && physicsScore >= 7.5 && chemistryScore >= 7.5;
        } else if (major.name.includes('Quản trị kinh doanh')) {
            // Tiêu chí A01: Toán >= 7.5, Lý >= 7.0, Anh >= 8.0
            return mathScore >= 7.5 && physicsScore >= 7.0 && englishScore >= 8.0;
        }
        
        return true; // Hiển thị tất cả nếu không match điều kiện cụ thể
    });
    
    return filteredMajors.length > 0 ? filteredMajors : demoMajors;
}