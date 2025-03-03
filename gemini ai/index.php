<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Gemini AI Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #f0f2f5;
            --text-color: #333;
            --chat-bg: white;
            --user-msg-bg: #0084ff;
            --ai-msg-bg: #e4e6eb;
            --input-bg: white;
            --input-border: #ddd;
            --send-btn-bg: #0084ff;
            --send-btn-hover: #005bbd;
            --toggle-bg: #ccc;
            --toggle-active: #0084ff;
        }

        .dark-mode {
            --bg-color: #181818;
            --text-color: #e0e0e0;
            --chat-bg: #242424;
            --user-msg-bg: #0084ff;
            --ai-msg-bg: #3a3b3c;
            --input-bg: #3a3b3c;
            --input-border: #555;
            --send-btn-bg: #0084ff;
            --send-btn-hover: #005bbd;
            --toggle-bg: #0084ff;
            --toggle-active: #ccc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        .chat-container {
            width: 90%;
            max-width: 800px;
            height: 90vh;
            background: var(--chat-bg);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: background-color 0.5s ease;
        }

        .chat-header {
            background: var(--user-msg-bg);
            color: white;
            padding: 15px;
            font-size: 1.2em;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.5s ease;
        }

        .chat-box {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            margin: 8px 0;
            border-radius: 20px;
            line-height: 1.4;
            word-wrap: break-word;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.3s ease-out forwards;
        }

        .message p {
            margin: 0 0 10px 0;
        }

        .message p:last-child {
            margin-bottom: 0;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            background: var(--user-msg-bg);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .ai-message {
            background: var(--ai-msg-bg);
            color: var(--text-color);
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        .user-input-container {
            display: flex;
            padding: 15px;
            background: var(--chat-bg);
            border-top: 1px solid var(--input-border);
            transition: background-color 0.5s ease;
        }

        .user-input {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid var(--input-border);
            border-radius: 25px;
            outline: none;
            font-size: 1em;
            background: var(--input-bg);
            color: var(--text-color);
            transition: border-color 0.3s, background-color 0.5s ease, color 0.5s ease;
        }

        .user-input:focus {
            border-color: var(--send-btn-bg);
        }

        .btn-send {
            background: var(--send-btn-bg);
            color: white;
            border: none;
            padding: 12px 20px;
            margin-left: 10px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-send:hover {
            background: var(--send-btn-hover);
        }

        .btn-send:active {
            transform: scale(0.95);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--toggle-bg);
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--toggle-active);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 8px 0;
            border-radius: 20px;
            align-self: flex-start;
            background: var(--ai-msg-bg);
            color: var(--text-color);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.3s ease-out forwards;
        }

        .typing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: currentColor;
            margin: 0 2px;
            opacity: 0.6;
            animation: typingAnimation 1.4s infinite ease-in-out both;
        }

        .typing-dot:nth-child(1) { animation-delay: 0s; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingAnimation {
            0%, 80%, 100% { transform: scale(0.6); }
            40% { transform: scale(1); }
        }

        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background-color: rgba(155, 155, 155, 0.5);
            border-radius: 20px;
        }

        .dark-mode .chat-box::-webkit-scrollbar-thumb {
            background-color: rgba(155, 155, 155, 0.3);
        }

        @media (max-width: 600px) {
            .chat-container {
                width: 100%;
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <span>Gemini AI Chat</span>
            <label class="toggle-switch">
                <input type="checkbox" id="darkModeToggle" onclick="toggleDarkMode()">
                <span class="slider"></span>
            </label>
        </div>
        <div id="chat-box" class="chat-box">
            <div class="message ai-message">
                <p>Hello! How can I assist you today?</p>
            </div>
        </div>
        <div class="user-input-container">
            <input type="text" id="user_input" class="user-input" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button class="btn-send" onclick="generatePrompt()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
        let isProcessing = false;

        function generatePrompt() {
            if (isProcessing) return;

            var userInput = document.getElementById("user_input").value.trim();
            if (userInput === "") return;

            isProcessing = true;
            var chatBox = document.getElementById("chat-box");
            
            // Add user message
            addMessage(userInput, 'user-message');
            
            // Clear input and disable
            document.getElementById("user_input").value = "";
            document.getElementById("user_input").disabled = true;
            document.querySelector(".btn-send").disabled = true;
            
            // Show typing indicator
            addTypingIndicator();

            // Make API request
            fetch("gemini_prompter.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "user_input=" + encodeURIComponent(userInput)
            })
            .then(response => response.json())
            .then(data => {
                removeTypingIndicator();
                
                // Add AI response
                addMessage(data.success ? data.message.replace(/\*/g, "") : "Error: " + data.message, 'ai-message');
                
                enableInput();
            })
            .catch(error => {
                removeTypingIndicator();
                
                // Add error message
                addMessage("Sorry, there was an error processing your request.", 'ai-message');
                
                enableInput();
            });
        }

        function addMessage(content, className) {
            var chatBox = document.getElementById("chat-box");
            var messageDiv = document.createElement("div");
            messageDiv.className = "message " + className;
            
            // Split content into paragraphs and wrap each in a <p> tag
            var paragraphs = content.split('\n\n');
            paragraphs.forEach(paragraph => {
                if (paragraph.trim() !== '') {
                    var p = document.createElement('p');
                    p.textContent = paragraph.trim();
                    messageDiv.appendChild(p);
                }
            });

            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function addTypingIndicator() {
            var chatBox = document.getElementById("chat-box");
            var typingIndicator = document.createElement("div");
            typingIndicator.className = "typing-indicator";
            typingIndicator.id = "typing-indicator";
            typingIndicator.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
            chatBox.appendChild(typingIndicator);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function removeTypingIndicator() {
            var indicator = document.getElementById("typing-indicator");
            if (indicator) {
                indicator.remove();
            }
        }

        function enableInput() {
            document.getElementById("user_input").disabled = false;
            document.querySelector(".btn-send").disabled = false;
            document.getElementById("user_input").focus();
            isProcessing = false;
        }

        function handleKeyPress(event) {
            if (event.key === "Enter" && !isProcessing) {
                generatePrompt();
            }
        }

        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        }

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            document.getElementById('darkModeToggle').checked = true;
        }
    </script>
</body>
</html>

