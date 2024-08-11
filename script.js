document.addEventListener('DOMContentLoaded', function () {
    var trys = 1;
                setTimeout(function(){
                    document.getElementById('loading_bar').style.width = '110%';
                },50)
                setTimeout(function(){document.getElementById('outer_loading_bar').style.opacity = '0'},8500)
        const chatContainer = document.getElementById('chat');

        // Funktion zum Hinzufügen von Nachrichten zum Chat-Container
        function addMessage(username, text) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message');
            
            const usernameElement = document.createElement('span');
            usernameElement.classList.add('username');
            usernameElement.textContent = username;
            
            const textElement = document.createElement('span');
            textElement.classList.add('text');
            textElement.textContent = text;
            
            messageElement.appendChild(usernameElement);
            messageElement.appendChild(textElement);
            chatContainer.appendChild(messageElement);
            
            // Scrollen zum neuesten Chat
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        // Funktion zum Abrufen der neuesten Chat-Nachrichten
        function fetchChatMessages() {
            fetch('get'+trys+'.php')
                .then(response => response.json())
                .then(data => {
                    chatContainer.innerHTML = ''; // Leere den Chat-Container
                    data.forEach(message => {
                        addMessage(message.username, message.message);
                    });
                })
                .catch(error => console.error('Fehler beim Abrufen des Chats:', error));
        }
        setInterval(fetchChatMessages, 15000);
        setTimeout(function(){
        setInterval(function(){
            if(chatContainer.innerHTML == ''){
                    trys++;
                    chatContainer.innerHTML = "<div style='position:fixed;bottom:50%;margin:20px;user-select:none;'>Desaux ist nicht live auf YouTube.<br>Liegt hier ein Fehler vor? Kontaktiere einfachniemmand!</div>"
                    if(trys == 6){
                        trys = 1;
                    }
                }
                },1)
        },20000)
            });