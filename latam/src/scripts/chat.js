function toggleChat() {
  const chat = document.querySelector(".chat");
  const toggleChat = document.querySelector(".toggle-chat");
  chat.classList.toggle("collapsed");
  toggleChat.classList.toggle("collapsed");
}
