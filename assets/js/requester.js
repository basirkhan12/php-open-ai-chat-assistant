const chatWindow = document.getElementById('chatWindow');
const userInput = document.getElementById('userInput');
const sendButton = document.getElementById('sendButton');
const fetchMessagesButton = document.getElementById('fetchMessagesButton');
let threadId = localStorage.getItem('threadId') || null;
let lastMessageId = localStorage.getItem('lastMessageId') || null;

// Initialize Markdown parser before using it
const md = window.markdownit();

// Load chat history from localStorage
const chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];
chatHistory.forEach(msg => appendMessage(msg.role, msg.text));

// Append message to the chat window
function appendMessage(role, text) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message', role);
    // Parse the message as markdown and add it to the chat window
    messageDiv.innerHTML = md.render(text); // Using markdown-it to render markdown into HTML
    chatWindow.appendChild(messageDiv);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

function convertTextToLink(text) {
    const regex = /\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g;
    return text.replace(regex, '<a href="$2" target="_blank">$1</a>');
}

// Show assistant typing animation
function showTypingAnimation() {
    const typingDiv = document.createElement('div');
    typingDiv.classList.add('message', 'assistant', 'typing');
    typingDiv.innerHTML = `<span>Assistant is typing...</span>`;
    chatWindow.appendChild(typingDiv);
    chatWindow.scrollTop = chatWindow.scrollHeight;
    return typingDiv;
}

// Save chat history to localStorage
function saveChatHistory() {
    localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
}

// Trigger send button click on Enter
userInput.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        sendButton.click();
    }
});

const beepSound = document.getElementById('beepSound');

function playBeepSound() {
    beepSound.play();
}

// Send user message and get assistant response
sendButton.addEventListener('click', async () => {
    const userMessage = userInput.value.trim();
    if (userMessage === "") return;

    userInput.disabled = true;
    sendButton.disabled = true;

    appendMessage('user', userMessage);
    chatHistory.push({ role: 'user', text: userMessage });
    saveChatHistory();

    const typingDiv = showTypingAnimation();

    const assistantMessage_main = await sendMessageToAPI(userMessage);
    //  const assistantMessage_link = convertTextToLink(assistantMessage_main);
    const assistantMessage = assistantMessage_main;

    chatWindow.removeChild(typingDiv);
    appendMessage('assistant', assistantMessage);

    chatHistory.push({ role: 'assistant', text: assistantMessage });
    saveChatHistory();

    playBeepSound();

    userInput.value = '';
    userInput.disabled = false;
    sendButton.disabled = false;
    userInput.focus();
});

async function sendMessageToAPI(message) {
    try {
        const response = await fetch('api/chatbot_backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message, threadId, lastMessageId })
        });
        const data = await response.json();
        threadId = data.threadId;
        lastMessageId = data.lastMessageId;  // Get the new lastMessageId from the response
        localStorage.setItem('threadId', threadId);
        localStorage.setItem('lastMessageId', lastMessageId);
        return data.reply;
    } catch (error) {
        console.error('Error:', error);
        return "Error fetching the response.";
    }
}

// Fetch new messages manually on button click
fetchMessagesButton.addEventListener('click', async () => {
    await checkForNewMessages();
});

// Observer for fetching new messages
async function checkForNewMessages() {
    try {
        const response = await fetch(`api/chatbot_backend.php?threadId=${threadId}&lastMessageId=${lastMessageId}`);
        const data = await response.json();

        if (data.newMessages) {
            data.newMessages.forEach(msg => {
                if (msg.role === 'assistant' && msg.id !== lastMessageId) {
                    appendMessage('assistant', msg.content.text.value);
                    chatHistory.push({ role: 'assistant', text: msg.content.text.value });
                    lastMessageId = msg.id;
                    localStorage.setItem('lastMessageId', lastMessageId);
                }
            });
            saveChatHistory();
        }
    } catch (error) {
        console.error('Error fetching new messages:', error);
    }
}