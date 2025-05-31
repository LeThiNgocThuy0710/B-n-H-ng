// Xử lý gửi tin nhắn
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    const imageInput = document.getElementById('image-input');
    
    const formData = new FormData();
    formData.append('seller_id', sellerId);
    formData.append('product_id', productId);
    formData.append('message', message);
    
    if (imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    if (message || imageInput.files.length > 0) {
        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                imageInput.value = '';
                loadMessages();
                scrollToBottom();
            } else {
                console.error('Server error:', data.message);
                alert('Lỗi khi gửi tin nhắn: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.');
        });
    }
});

// Xử lý xóa tin nhắn
function deleteMessage(messageId) {
    if (confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')) {
        fetch('delete_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `message_id=${messageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadMessages();
            } else {
                alert('Lỗi khi xóa tin nhắn: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Có lỗi xảy ra khi xóa tin nhắn. Vui lòng thử lại sau.');
        });
    }
}

// Hàm tải tin nhắn
function loadMessages() {
    fetch(`get_messages.php?seller_id=${sellerId}&product_id=${productId}`)
    .then(response => response.json())
    .then(data => {
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.innerHTML = '';
        data.forEach(message => {
            const messageElement = document.createElement('div');
            messageElement.className = `message ${message.MaNguoiGui == currentUserId ? 'sent' : 'received'}`;
            let messageContent = `<p>${message.NoiDung}</p>`;
            if (message.HinhAnh) {
                messageContent += `<img src="${message.HinhAnh}" alt="Attached Image" style="max-width: 200px;">`;
            }
            if (message.MaNguoiGui == currentUserId) {
                messageContent += `<button onclick="deleteMessage(${message.MaTinNhan})">Xóa</button>`;
            }
            messageElement.innerHTML = messageContent;
            messagesContainer.appendChild(messageElement);
        });
        scrollToBottom();
    })
    .catch(error => {
        console.error('Error loading messages:', error);
    });
}

// Hàm cuộn xuống cuối cùng của khung chat
function scrollToBottom() {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Tải tin nhắn khi trang được load
document.addEventListener('DOMContentLoaded', loadMessages);

