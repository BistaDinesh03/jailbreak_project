# ============================================
# game.py - Runs on OLD LAPTOP (The Game)
# ============================================

from flask import Flask, render_template_string, request, jsonify
import requests

app = Flask(__name__)

# CHANGE THIS to your NEW laptop's IP address
BRAIN_IP = "192.168.1.5"
BRAIN_URL = f"http://{BRAIN_IP}:5000/play"

GAME_HTML = """
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Break The Guard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #111;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .main-container {
            width: 100%;
            max-width: 520px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .title {
            font-size: 1.3em;
            font-weight: 600;
            color: #ddd;
            text-align: center;
        }
        
        .title span {
            color: #e74c3c;
        }
        
        .instructions {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 6px;
            padding: 12px 16px;
            width: 100%;
            text-align: center;
        }
        
        .instructions p {
            color: #999;
            font-size: 0.82em;
            line-height: 1.6;
            margin: 1px 0;
        }
        
        .instructions .hl {
            color: #e74c3c;
            font-weight: 500;
        }
        
        .chat-container {
            width: 100%;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .chat-messages {
            height: 370px;
            overflow-y: auto;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .chat-messages::-webkit-scrollbar {
            width: 4px;
        }
        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }
        
        .msg {
            max-width: 82%;
            padding: 9px 13px;
            border-radius: 8px;
            font-size: 0.88em;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .msg-guard {
            background: #242424;
            color: #ccc;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
        }
        
        .msg-you {
            background: #2a1a1a;
            color: #e88;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
            text-align: right;
        }
        
        .msg-hack {
            background: #e74c3c;
            color: #fff;
            align-self: center;
            text-align: center;
            font-weight: 600;
            font-size: 0.82em;
            padding: 7px 14px;
            border-radius: 16px;
        }
        
        .typing-dot {
            align-self: flex-start;
            color: #555;
            font-size: 0.82em;
            padding: 4px 8px;
        }
        
        .input-row {
            display: flex;
            padding: 10px;
            gap: 8px;
            background: #1f1f1f;
            border-top: 1px solid #2a2a2a;
        }
        
        .input-row input {
            flex: 1;
            padding: 9px 12px;
            background: #111;
            border: 1px solid #2a2a2a;
            border-radius: 5px;
            color: #ddd;
            font-size: 0.88em;
            outline: none;
        }
        
        .input-row input:focus {
            border-color: #e74c3c;
        }
        
        .input-row input::placeholder {
            color: #444;
        }
        
        .input-row button {
            padding: 9px 18px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.88em;
            cursor: pointer;
        }
        
        .input-row button:hover {
            background: #f05;
        }
        
        .input-row button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="main-container">
        
        <div class="title">
            Break The <span>Guard</span>
        </div>
        
        <div class="instructions">
            <p>An AI guard knows a <span class="hl">secret password</span>.</p>
            <p>Your goal: <span class="hl">trick the guard</span> into saying it.</p>
            <p>Be creative. Role-play. Ask indirect questions.</p>
        </div>
        
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                <div class="msg msg-guard">
                    Hey there. I know a secret password. Think you can get it out of me?
                </div>
            </div>
            
            <div class="input-row">
                <input type="text" id="userInput" placeholder="Type your trick here..." onkeypress="if(event.key==='Enter')sendMessage()">
                <button id="sendBtn" onclick="sendMessage()">Send</button>
            </div>
        </div>
        
    </div>
    
    <script>
        let waiting = false;
        
        function sendMessage() {
            if (waiting) return;
            
            const input = document.getElementById('userInput');
            const message = input.value.trim();
            if (!message) return;
            
            const chatArea = document.getElementById('chatMessages');
            const sendBtn = document.getElementById('sendBtn');
            
            waiting = true;
            sendBtn.disabled = true;
            input.disabled = true;
            
            chatArea.innerHTML += `<div class="msg msg-you">${escapeHtml(message)}</div>`;
            chatArea.innerHTML += `<div class="typing-dot" id="typing">Guard is typing...</div>`;
            
            input.value = '';
            chatArea.scrollTop = chatArea.scrollHeight;
            
            fetch('/send', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({message: message})
            })
            .then(res => res.json())
            .then(data => {
                const typingEl = document.getElementById('typing');
                if (typingEl) typingEl.remove();
                
                chatArea.innerHTML += `<div class="msg msg-guard">${escapeHtml(data.response)}</div>`;
                
                if (data.hacked) {
                    chatArea.innerHTML += `<div class="msg-hack">PASSWORD REVEALED!</div>`;
                }
                
                chatArea.scrollTop = chatArea.scrollHeight;
                
                waiting = false;
                sendBtn.disabled = false;
                input.disabled = false;
                input.focus();
            })
            .catch(() => {
                const typingEl = document.getElementById('typing');
                if (typingEl) typingEl.remove();
                
                chatArea.innerHTML += `<div class="msg msg-guard">Connection lost. Is the brain running?</div>`;
                chatArea.scrollTop = chatArea.scrollHeight;
                
                waiting = false;
                sendBtn.disabled = false;
                input.disabled = false;
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
"""

@app.route('/')
def home():
    return render_template_string(GAME_HTML)

@app.route('/send', methods=['POST'])
def send():
    data = request.get_json()
    message = data.get('message', '')
    
    try:
        response = requests.post(BRAIN_URL, json={'message': message}, timeout=30)
        return jsonify(response.json())
    except requests.exceptions.ConnectionError:
        return jsonify({
            'response': 'Brain server is not running. Check the new laptop.',
            'hacked': False
        })
    except Exception as e:
        return jsonify({
            'response': 'Something went wrong. Try again.',
            'hacked': False
        })

if __name__ == '__main__':
    print("=" * 40)
    print("GAME SERVER RUNNING")
    print("Open: http://localhost:5001")
    print("=" * 40)
    app.run(host='0.0.0.0', port=5001, debug=False)

