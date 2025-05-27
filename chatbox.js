function toggleChatbox() {
    const chatbox = document.getElementById('chatbotWindow');
    chatbox.style.display = chatbox.style.display === 'block' ? 'none' : 'block';
    
    // Auto-scroll ke bawah saat chatbox dibuka
    if (chatbox.style.display === 'block') {
        scrollToBottom();
    }
}

function scrollToBottom() {
    const chatHistory = document.querySelector('.chat-history');
    if (chatHistory) {
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }
}

// Panggil scrollToBottom saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});