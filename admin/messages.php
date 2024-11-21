<?php
require 'dbconnect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$sql = "
  SELECT 
    u.user_id, 
    u.user_name,
    COUNT(m.id) AS unread_count,
    (SELECT m2.message FROM chat_messages m2 WHERE m2.user_id = u.user_id ORDER BY m2.timestamp DESC LIMIT 1) AS latest_message,
    (SELECT m2.admin_id FROM chat_messages m2 WHERE m2.user_id = u.user_id ORDER BY m2.timestamp DESC LIMIT 1) AS latest_sender,
    (SELECT m2.timestamp FROM chat_messages m2 WHERE m2.user_id = u.user_id ORDER BY m2.timestamp DESC LIMIT 1) AS latest_timestamp
  FROM 
    royale_user_tbl AS u
  LEFT JOIN 
    chat_messages AS m 
  ON 
    u.user_id = m.user_id 
    AND m.admin_id IS NULL
    AND m.admin_reply IS NULL
  GROUP BY  
    u.user_id, u.user_name
  HAVING 
    latest_message IS NOT NULL AND latest_message != 'No messages yet'
  ORDER BY 
    latest_timestamp DESC
";


$result = $conn->query($sql);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <?php include 'important.php'; ?>
    <link rel="stylesheet" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/all_dashboard.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="../system_images/whitelogo.png" type="image/png">
</head>

<body class="<?php echo isset($_SESSION['admin_id']) ? 'admin-mode' : ''; ?>">

    <div class="overall-container">
        <?php include 'sidenav.php'; ?>

        <main>
            <div class="header-container">
                <div class="header-label-container">
                    <i class="fa-solid fa-envelope"></i>
                    <label for="">Messages</label>
                </div>
                <?php include 'header_icons_container.php'; ?>
            </div>

            <div class="content-container">
                <style>
                    .content {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px;
                        padding: 20px;
                    }

                    .user-card {
                        position: relative;
                        background-color: var(--second-bgcolor);
                        color: var(--text-color);
                        border-radius: 5px;
                        border: 1px solid var(--box-shadow);
                        padding: 10px;

                        cursor: pointer;
                        transition: transform 0.2s, box-shadow 0.2s;
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                    }

                    .user-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);
                    }

                    .user-info {
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                        text-align: left;
                    }

                    .user-info h2 {
                        margin: 0;
                        font-size: 1.5rem;
                        color: var(--primary-color);
                    }

                    .latest-message {
                        font-size: 1.2rem;
                        color: var(--text-color2);
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .unread-badge {
                        position: absolute;
                        top: 10px;
                        right: 10px;
                        background-color: #e74c3c;
                        width: 10px;
                        height: 10px;
                        border-radius: 50%;
                        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
                    }

                    .search-bar-container {
                    
                        display: flex;
                        justify-content: flex-start;
                    }

                    #search-bar {
                        padding: 10px;
                        width: 100%;
                        max-width: 300px;
                        border: 1px solid var(--box-shadow);
                        border-radius: 5px;
                        font-size: 1.5rem;
                        background-color: var(--second-bgcolor);
                        color: var(--text-color);
                    }
                </style>

                <div class="content">

                    <!-- Search Bar Container -->
                    <div class="search-bar-container">
                        <input type="text" id="search-bar" placeholder="Search users..." onkeyup="searchUsers()" />
                    </div>

                    <script>
                        function searchUsers() {
                            const searchTerm = document.getElementById('search-bar').value.toLowerCase();
                            const userCards = document.querySelectorAll('.user-card');

                            userCards.forEach(card => {
                                const userName = card.querySelector('h2').textContent.toLowerCase();
                                if (userName.includes(searchTerm)) {
                                    card.style.display = 'flex'; // Show the card if there's a match
                                } else {
                                    card.style.display = 'none'; // Hide the card if no match
                                }
                            });
                        }
                    </script>







                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $user_name = htmlspecialchars($row['user_name']);
                        $unread_count = $row['unread_count'];
                        $latest_message = htmlspecialchars($row['latest_message']);
                        $latest_sender = $row['latest_sender'];  // Get the latest sender
                    ?>
                        <div class="user-card" onclick="openChatbox(<?php echo $user_id; ?>, '<?php echo addslashes($user_name); ?>')">
                            <div class="user-info">
                                <h2><?php echo $user_name; ?></h2>

                                <!-- Check if latest message is not 'No messages yet' and show the unread badge accordingly -->
                                <?php if ($latest_message !== 'No messages yet' && $latest_message !== null && ($latest_sender === null || $latest_sender != $_SESSION['admin_id'])): ?>
                                    <span class="unread-badge"></span>
                                <?php endif; ?>

                                <div class="latest-message">
                                    <!-- Display the latest message or show 'No messages yet' if it's empty -->
                                    Last Message: <?php echo $latest_message ? htmlspecialchars($latest_message) : 'No messages yet'; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </main>
    </div>

    <script>
        function openChatbox(userId, userName) {
            console.log(`Open chatbox for ${userName} (ID: ${userId})`);
        }
    </script>
</body>

</html>




<!-- Chatbox Container -->
<div class="chatbox-container" id="chatbox" style="display: none;">
    <div class="chatbox-header">
        <i class="fa-brands fa-web-awesome"></i>
        <h3 id="user-name-header">R O Y A L E</h3> <!-- Placeholder for user name -->
        <button class="chatbox-close" onclick="toggleChatbox()">X</button>
    </div>
    <div class="chatbox-body">
        <em style="font-size:1rem; padding-left: 10px;">use this chat box for important concerns only</em>
        <div class="messages">
            <!-- Messages will go here -->
        </div>
    </div>
    <div class="chatbox-footer">
        <input type="text" class="chatbox-input" id="chat-input" placeholder="Type your message..." />
        <button class="chatbox-send" onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
    let currentUserId = null;

    function openChatbox(userId, userName) {
        currentUserId = userId; // Store the clicked user's ID
        const chatbox = document.getElementById('chatbox');
        chatbox.style.display = 'flex'; // Show the chatbox

        // Update the chatbox header with the clicked user's name
        const userNameHeader = document.getElementById('user-name-header');
        userNameHeader.textContent = userName; // Set the user name dynamically

        loadMessages(userId); // Load messages for the selected user
    }



    function loadMessages(userId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_messages.php?user_id=' + userId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const messages = JSON.parse(xhr.responseText);
                const messagesContainer = document.querySelector('.messages');
                messagesContainer.innerHTML = ''; // Clear existing messages

                messages.forEach(function(message) {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', message.is_admin ? 'admin-message' : 'user-message');

                    // Create the message text
                    const messageText = document.createElement('div');
                    messageText.classList.add('message-text');
                    messageText.textContent = message.message;
                    messageDiv.appendChild(messageText);

                    // Create the timestamp
                    const timestampDiv = document.createElement('div');
                    timestampDiv.classList.add('message-timestamp');
                    timestampDiv.textContent = formatTimestamp(message.timestamp); // Format the timestamp
                    messageDiv.appendChild(timestampDiv);

                    messagesContainer.appendChild(messageDiv);
                });

                // Scroll to the bottom of the chat
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
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

    // Add styles for admin and user messages


    // Send a message
    function sendMessage() {
        const message = document.getElementById('chat-input').value.trim();
        if (message && currentUserId) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'store_message.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    loadMessages(currentUserId); // Reload the messages
                    document.getElementById('chat-input').value = ''; // Clear input
                }
            };
            xhr.send('message=' + encodeURIComponent(message) + '&user_id=' + currentUserId);
        }
    }

    // Close chatbox
    function toggleChatbox() {
        const chatbox = document.getElementById('chatbox');
        chatbox.style.display = chatbox.style.display === 'none' ? 'flex' : 'none';
    }
</script>


















<style>
    .message-timestamp {
        font-size: 0.9rem;
        color: gray;
        text-align: right;
        margin-top: 5px;
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
        transform: translateY(20px);
        /* Start with a slight translation */
        transition: opacity 0.3s ease, transform 0.3s ease;
        /* Smooth transition */
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

    /* Messages container styling */
    .messages {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px;
        max-height: 300px;
        overflow-y: auto;
    }

    /* User message (left-aligned) */
    .user-message {
        align-self: flex-start;
        background-color: #f1f1f1;
        color: #000;
        padding: 8px 12px;
        border-radius: 15px 15px 15px 0;
        max-width: 70%;
        font-size: 1.4rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--box-shadow);
    }

    /* Admin message (right-aligned) */
    .admin-message {
        align-self: flex-end;
        background-color: #003f6f;
        color: white;
        padding: 8px 12px;
        border-radius: 15px 15px 0 15px;
        max-width: 70%;
        font-size: 1.4rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--box-shadow);
    }

    /* Chatbox Body Scroll */
    .chatbox-body {
        flex-grow: 1;

        overflow-y: auto;
        display: flex;
        flex-direction: column;
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
</style>