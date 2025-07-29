let chatVisible = false;

function toggleChat() {
  chatVisible = !chatVisible;
  document.getElementById('chatbox').style.display = chatVisible ? 'flex' : 'none';
}

function sendMessage() {
  const input = document.getElementById("userInput");
  const message = input.value.trim();
  if (message === "") return;

  appendMessage("user", message);
  input.value = "";

  // Hiển thị "đang nhập..." để user biết bot đang xử lý
  const typingDiv = document.createElement("div");
  typingDiv.innerHTML = `<strong>Hỗ trợ:</strong> Đang nhập...`;
  typingDiv.id = "typing";
  document.getElementById("chatMessages").appendChild(typingDiv);

  fetch("./assets/tuyensinh/data.json")
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      // Xóa "đang nhập..."
      const typingElement = document.getElementById("typing");
      if (typingElement) {
        typingElement.remove();
      }

      const lowerMessage = message.toLowerCase();
      let found = data.find(item => lowerMessage.includes(item.question.toLowerCase()));
      let reply = found ? found.answer : "Xin lỗi, tôi chưa hiểu câu hỏi. Bạn có thể hỏi về: học phí, điểm chuẩn, thời gian nhập học, điều kiện xét học bạ, hoặc thông tin tuyển sinh.";
      appendMessage("bot", reply);
    })
    .catch(error => {
      // Xóa "đang nhập..."
      const typingElement = document.getElementById("typing");
      if (typingElement) {
        typingElement.remove();
      }

      console.error('Error fetching data:', error);
      appendMessage("bot", "Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.");
    });
}

function appendMessage(sender, text) {
  const msgBox = document.getElementById("chatMessages");
  const div = document.createElement("div");
  div.innerHTML = `<strong>${sender === "user" ? "Bạn" : "Hỗ trợ"}:</strong> ${text}`;
  msgBox.appendChild(div);
  msgBox.scrollTop = msgBox.scrollHeight;
} 