<!-- Chat Icon -->
<div class="chat-icon" onclick="toggleChatbox()">
    <i class="fa-solid fa-message"></i>
    <!-- Red Dot Notification -->
    <span class="red-dot" id="redDotNotification" style="display: none;"></span>
</div>


<style>
    /* Red dot notification */
    .red-dot {
        position: absolute;
        top: -1px;
        right: -1px;
        width: 12px;
        height: 12px;
        background-color: red;
        border-radius: 50%;
        z-index: 10;
    }
</style>
<!-- Chatbox Container -->
<div class="chatbox-container" id="chatbox" style="display: none;">
    <div class="chatbox-header">
        <i class="fa-brands fa-web-awesome"></i>
        <h3>R O Y A L E </h3>
        <button class="chatbox-close" onclick="toggleChatbox()">X</button>
    </div>
    <div class="chatbox-body">
        <em style="font-size:1rem; padding-left: 10px;">use this chat box for important concerns only</em>
        <div class="messages">
            <!-- Messages will go here -->
        </div>

    </div>
    <div class="chatbox-footer">
        <input type="text" class="chatbox-input" placeholder="Type your message..." />
        <button class="chatbox-send">Send</button>
    </div>
</div>



<style>
    /* Chat Icon */
    .chat-icon {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--first-bgcolor);
        border-radius: 50%;
        padding: 13px 12px 9px 13px;
        cursor: pointer;
        border: var(--box-shadow) 1px solid;
        box-shadow: 0 2px 4px var(--hover-color);

        transition: transform 0.3s ease;
        /* Smooth scaling transition */
        color: var(--text-color);
        font-size: 3rem;
        z-index: 999;
    }


    /* Chatbox Container */
    .chatbox-container {
        width: 300px;
        height: 400px;
        background-color: var(--first-bgcolor);
        border: 1px solid var(--box-shadow);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: fixed;
        bottom: 70px;
        right: 20px;
        z-index: 1000;
        display: none;
        /* Initially hidden */
        opacity: 0;
        transform: translateY(20px);
        /* Start with a slight translation */
        transition: opacity 0.3s ease, transform 0.3s ease;
        /* Smooth transition */
    }

    .chatbox-container.show {
        opacity: 1;
        transform: translateY(0);
        /* Animate to the original position */
    }

    .chatbox-header {
        background-color: var(--text-color);
        color: var(--first-bgcolor);
        padding: 10px;
        text-align: center;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.5rem;
    }

    .chatbox-header h3 {
        margin: 0;
        font-weight: bold;
    }

    .chatbox-close {
        background-color: transparent;
        color: var(--first-bgcolor);
        border: none;
        font-size: 18px;
        cursor: pointer;
    }

    .chatbox-body {
        flex-grow: 1;
        overflow-y: auto;
        background-color: var(--first-bgcolor);
    }

    /* Messages container */
    .messages {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
        max-height: 300px;
        overflow-y: auto;
        background-color: var(--first-bgcolor);
        scrollbar-width: thin;
        scrollbar-color: var(--box-shadow) var(--first-bgcolor);
    }

    /* Scrollbar styling */
    .messages::-webkit-scrollbar {
        width: 8px;
    }

    .messages::-webkit-scrollbar-thumb {
        background-color: var(--box-shadow);
        border-radius: 4px;
    }

    .messages::-webkit-scrollbar-track {
        background-color: var(--first-bgcolor);
    }

    /* User's message (align right) */
    .message.user-message {
        align-self: flex-end;
        background-color: #001C31;
        /* Light green for user */
        color: white;
        padding: 8px 12px;
        border-radius: 15px 15px 0 15px;
        max-width: 70%;
        font-size: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--box-shadow);
    }

    /* Admin's message (align left) */
    .message.admin-message {
        align-self: flex-start;
        background-color: #f1f1f1;
        /* Light gray for admin */
        color: #333;
        padding: 8px 12px;
        border-radius: 15px 15px 15px 0;
        max-width: 70%;
        font-size: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--box-shadow);
    }


    .chatbox-footer {
        padding: 10px;
        display: flex;
        justify-content: space-between;
        background-color: #f1f1f1;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        align-items: center;
    }

    .chatbox-input {
        width: 80%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .chatbox-send {
        width: 15%;
        padding: 8px;
        background-color: #001C31;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .chatbox-send:hover {
        background-color: #003f6f;
    }

    .message-timestamp {
    font-size: 0.8rem;
    color:gray;
    text-align: right;
    margin-top: 5px;
  
}

</style>

<script>
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

    function loadMessages(userId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_messages.php?user_id=' + userId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const messages = JSON.parse(xhr.responseText);
                const messagesContainer = document.querySelector('.messages');
                messagesContainer.innerHTML = ''; // Clear the container before adding new messages

                let lastMessageFromAdmin = false; // Flag to check if the last message is from admin

                messages.forEach(function(message) {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');

                    // Apply message class based on admin_id
                    if (message.is_admin) {
                        messageDiv.classList.add('admin-message');
                        lastMessageFromAdmin = true; // Set flag if it's an admin's message
                    } else {
                        messageDiv.classList.add('user-message');
                    }

                    // Create message text
                    const messageText = document.createElement('div');
                    messageText.classList.add('message-text');
                    messageText.textContent = message.message;
                    messageDiv.appendChild(messageText);

                    // Create timestamp div
                    const timestampDiv = document.createElement('div');
                    timestampDiv.classList.add('message-timestamp');
                    timestampDiv.textContent = formatTimestamp(message.timestamp); // Format and display timestamp
                    messageDiv.appendChild(timestampDiv);

                    // Append messageDiv to container
                    messagesContainer.appendChild(messageDiv);
                });

                // Scroll to the bottom of the chat
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Show or hide the red dot based on the last message
                const redDot = document.getElementById('redDotNotification');
                if (lastMessageFromAdmin) {
                    if (!sessionStorage.getItem('hasNewAdminMessage')) {
                        // If it's a new admin message and not viewed, show the red dot
                        redDot.style.display = 'block';
                        sessionStorage.setItem('hasNewAdminMessage', 'true'); // Mark it as viewed
                    }
                } else {
                    redDot.style.display = 'none'; // Hide if last message isn't from admin
                }
            }
        };
        xhr.send();
    }

    // Function to format the timestamp with date and time (e.g., "2024-11-21 14:35:22")
    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
        const day = date.getDate().toString().padStart(2, '0');
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }


    // Call loadMessages initially to fetch the latest chat messages when the page loads
    loadMessages();
</script>


<script>
    function toggleChatbox() {
        const chatbox = document.getElementById('chatbox');
        const chatIcon = document.querySelector('.chat-icon');
        const redDot = document.getElementById('redDotNotification');
        const messagesContainer = document.querySelector('.messages'); // Get the messages container

        if (chatbox.style.display === 'none' || chatbox.style.display === '') {
            chatbox.style.display = 'flex'; // Show the chatbox
            setTimeout(() => {
                chatbox.classList.add('show'); // Add animation class after display change
            }, 10); // Small delay for the transition to take effect

            chatIcon.style.display = 'none'; // Hide the chat icon
            chatIcon.style.transform = 'scale(0)'; // Shrink the chat icon

            // Clear the red dot flag when chatbox is opened
            sessionStorage.removeItem('hasNewAdminMessage');
            redDot.style.display = 'none'; // Hide the red dot as it's being viewed

            // Scroll to the bottom when the chatbox is shown
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            chatbox.classList.remove('show'); // Remove the animation class to hide
            setTimeout(() => {
                chatbox.style.display = 'none'; // Hide the chatbox after the animation
            }, 300); // Matches the transition duration

            chatIcon.style.display = 'block'; // Show the chat icon again
            chatIcon.style.transform = 'scale(1)'; // Reset the chat icon size
        }
    }



    document.querySelector('.chatbox-send').addEventListener('click', function() {
        const inputField = document.querySelector('.chatbox-input');
        const message = inputField.value.trim();



        if (!userId) {
            // If the user is not logged in, show SweetAlert and return early
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please login first to send a message!'
            });
            return; // Prevent further execution
        }

        if (message) {
            // Send the message to the server using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'store_message.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Display the new message in the chatbox
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', 'user-message'); // Add 'user-message' class
                    messageDiv.textContent = message;

                    const messagesContainer = document.querySelector('.messages');
                    messagesContainer.appendChild(messageDiv);

                    // Scroll to the bottom of the chat
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;

                    // Clear the input field after sending the message
                    inputField.value = '';
                }
            };
            xhr.send('message=' + encodeURIComponent(message));
        }
    });
</script>