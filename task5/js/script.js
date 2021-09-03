"use script"

document.addEventListener('DOMContentLoaded', function (){
    const form1 = document.getElementById('form1');
    const form2 = document.getElementById('form2');
    var button1 = document.getElementById('btn1');
    var button2 = document.getElementById('btn2');
    async function task1(){
        let formData = new FormData(form1);
        let login = document.getElementById('log1');
        if (!/([^A-Za-z0-9])/.test(login.value)){
        let response = await fetch('task.php', {
            method: 'POST',
            body: formData
        });
        let str = "";
        let result = await response.json();
        console.log(result.status);
        if (result.status == "ok"){
            result.result.forEach(pass => {
                str += pass['pass'] + "\n";
            });
        }
        else {
            str = result.result;
        }
        document.getElementById('ans1').innerHTML = str;
    }
    else {
        document.getElementById('ans1').innerHTML = "неверный логин";
    }
    }
    button1.onclick = task1;

    async function task2(){
        let formData = new FormData(form2);
        let response = await fetch('task.php', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();
        console.log(result);
        let str = "";
        if (result.status == "ok"){  
            result.result.forEach(pass => {
                str += pass['pass'] + "\n";
            });
        }
        else {
            str = result.result;
        }
        console.log(str);
        document.getElementById('ans2').innerHTML = str;
    }
    button2.onclick = task2;
    form2.reset();
});  