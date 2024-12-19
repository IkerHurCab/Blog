document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const chatContainer = document.getElementById('chatContainer');
    const chatUserId = messageForm.getAttribute('data-chat-user-id');

    function fetchMessages() {
        fetch(`user-chat.php?user_id=${chatUserId}&action=fetch`)
            .then(response => response.json())
            .then(messages => {
                chatContainer.innerHTML = '';
                messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    if (message.sent_by == userId) {
                        messageElement.classList.add('text-right');
                        messageElement.innerHTML = `
                            <p class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg mb-2">
                                ${message.message}
                            </p>
                        `;
                    } else {
                        messageElement.classList.add('text-left');
                        messageElement.innerHTML = `
                            <p class="inline-block bg-gray-700 text-white px-4 py-2 rounded-lg mb-2">
                                ${message.message}
                            </p>
                        `;
                    }
                    chatContainer.appendChild(messageElement);
                });

                chatContainer.scrollTop = chatContainer.scrollHeight;
            })
            .catch(error => console.error('Ha habido un error inesperado:', error));
    }

    setInterval(fetchMessages, 1000);
    fetchMessages();

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(messageForm);

        fetch(`user-chat.php?user_id=${chatUserId}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                messageInput.value = '';
                fetchMessages();
            } else {
                console.error('Error al intentar enviar el mensaje');
            }
        })
        .catch(error => console.error('Error enviando el mensaje:', error));
    });
});
