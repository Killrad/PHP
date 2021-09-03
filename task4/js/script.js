"use script"

document.addEventListener('DOMContentLoaded', function (){
    const form = document.getElementById('form');
    var button1 = document.getElementById('btn1');
    var button2 = document.getElementById('btn2');
    async function task1(){
        let formData = new FormData(form);
        let response = await fetch('task1.php', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();
        document.getElementById('ans1').innerHTML = "Ответ: " + result.result;
        form.reset();
    }
    button1.onclick = task1;

    async function task2(){
        let formData = new FormData(form);
        let response = await fetch('task2.php', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();
        document.getElementById('ans2').innerHTML = "Ответ: " + result.result;
        form.reset();
    }
    button2.onclick = task2;
    form.reset();
});  