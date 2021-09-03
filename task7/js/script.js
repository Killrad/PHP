"use script"

document.addEventListener('DOMContentLoaded', function (){
    const form1 = document.getElementById('form1');
    var button1 = document.getElementById('btn1');
    async function task1(){
        let formData = new FormData(form1);
        
        let response = await fetch('task.php', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();
        document.getElementById('ans1').innerHTML = "Ближайшее метро: " + result;
    }
    button1.onclick = task1;
});  