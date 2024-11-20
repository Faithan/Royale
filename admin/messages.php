<?php
require 'dbconnect.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$sql = "SELECT * FROM royale_user_tbl"; // Assuming you have a 'users' table
$result = $conn->query($sql);

// Fetch unread messages count for each user
function getUnreadMessagesCount($user_id)
{
    global $conn;

    // Fetch the unread count and determine if the last message was from the user or admin
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) AS unread_count, 
            MAX(CASE WHEN admin_id IS NULL AND admin_reply IS NULL THEN 1 ELSE 0 END) AS last_message_from_user,
            MAX(CASE WHEN admin_id IS NOT NULL AND admin_reply IS NULL THEN 1 ELSE 0 END) AS last_message_from_admin
        FROM 
            chat_messages
        WHERE 
            user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($unread_count, $last_message_from_user, $last_message_from_admin);
    $stmt->fetch();

    // Return the unread message count, whether the last message was from the user, and if the last message was from the admin
    return [
        'unread_count' => $unread_count,
        'last_message_from_user' => $last_message_from_user == 1,
        'last_message_from_admin' => $last_message_from_admin == 1
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <!-- Include your other stylesheets -->
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
                <div class="content">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $user_name = $row['user_name']; // User's name
                        $message_info = getUnreadMessagesCount($user_id); // Get unread message count and last message info
                        $unread_count = $message_info['unread_count'];
                        $last_message_from_user = $message_info['last_message_from_user']; // Whether the last message is from user
                        $last_message_from_admin = $message_info['last_message_from_admin']; // Whether the last message is from admin
                    ?>
                        <div class="user-card" onclick="openChatbox(<?php echo $user_id; ?>, '<?php echo addslashes($user_name); ?>')">
                            <div class="user-info">
                                <h2><?php echo $user_name; ?></h2> <!-- Display the user name in the card -->

                                <?php
                                // Show the last message with color based on who sent it
                                if ($last_message_from_user) {
                                    echo "<p class='last-message user-message'>Last message from user</p>"; // User message, red
                                } elseif ($last_message_from_admin) {
                                    echo "<p class='last-message admin-message'>Last message from admin</p>"; // Admin message, blue
                                }
                                ?>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </main>
    </div>

</body>

</html>



<style>
    .content {
        display: flex;
        flex-direction: row;
    }

    /* User message (red color for the last message from user) */
    .last-message.user-message {
        color: red;
        font-weight: bold;
    }

    /* Admin message (blue color for the last message from admin) */
    .last-message.admin-message {
        color: white;
        font-weight: bold;
        background-color:  blue;
        border: 1px solid var(--box-shadow);
        box-shadow: none;
    }

    /* Styling for the user-card remains as is */
    .user-card {
        position: relative;
        background-color: #f1f1f1;
        padding: 15px;
        margin: 10px;
        border-radius: 5px;
        cursor: pointer;
        background-color: var(--second-bgcolor);

    }

    .user-info{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        color: var(--text-color);
    }

    .user-info p {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }



  

 
</style>

<!-- Chatbox Container -->
<div class="chatbox-container" id="chatbox" style="display: none;">
    <div class="chatbox-header">
        <i class="fa-brands fa-web-awesome"></i>
        <h3 id="user-name-header">R O Y A L E</h3> <!-- Placeholder for user name -->
        <button class="chatbox-close" onclick="toggleChatbox()">X</button>
    </div>
    <div class="chatbox-body">
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
                    messageDiv.textContent = message.message;
                    messagesContainer.appendChild(messageDiv);
                });

                // Scroll to the bottom of the chat
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        };
        xhr.send();
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
        font-size: 1.2rem;
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