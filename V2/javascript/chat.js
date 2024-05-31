const form = document.querySelector(".compose-area"),
recipientField = form.querySelector(".recipient"),
subjectField = form.querySelector(".subject"),
messageField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
mailBox = document.querySelector(".mail-box");

form.onsubmit = (e)=>{
    e.preventDefault();
}

sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              recipientField.value = "";
              subjectField.value = "";
              messageField.value = "";
              loadMails(); // Charger les courriels après l'envoi
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

function loadMails(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            mailBox.innerHTML = data;
          }
      }
    }
    xhr.send();
}

// Charger les courriels au démarrage
loadMails();

  