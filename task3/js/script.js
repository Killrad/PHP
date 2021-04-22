"use script"


document.addEventListener('DOMContentLoaded', function (){
    const form = document.getElementById('form');
    const formS = document.getElementById('formSend');
    form.addEventListener('submit', formSend);
    formS.addEventListener('submit', formReset);

    async function formReset(e){
        formS.classList.add('_hide');
        form.classList.remove('_hide');
    }



    async function formSend(e) {
        e.preventDefault();
        document.getElementById('error_label').classList.add('_hide');
        let error = formValidate(form);
        let formData = new FormData(form);
        if (error === 0) {
            form.classList.add('_sending');
            let dbtest = await fetch('test.php', {
                method: 'POST',
                body: formData
            });
            let dbresult = await dbtest.json();
            if (dbresult.result == 'error') {
                form.classList.remove('_sending');
                alert(dbresult.status);
            } else if (dbresult.result == 'wait') {
                form.classList.remove('_sending');
                let time = dbresult.status.split(":");" | " + time[0]
                let timerStop = new Date().getTime();
                timerStop = timerStop + 1000*(Number(time[2]) + 60*(Number(time[1]) + 60*Number(time[0])));
                document.getElementById('error_label').classList.remove('_hide');
                document.getElementById('error_label').innerHTML = "Вы уже отправляли заявку недавно!";
                let x = setInterval(function() {
                    let now = new Date().getTime();
                    let distance = timerStop - now;
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById('error_label').innerHTML = "Вы уже отправляли заявку недавно!"+
                        " Повторите попытку через " + hours + ":" + minutes + ":" + seconds + ".";
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("error_label").innerHTML = "Вы можете отправить повторную заявку!";
                    }
                },1000);
            } else {
                let response = await fetch('sendmail.php', {
                    method: 'POST',
                    body: formData
                });
                let result = await response.json();
                if (result.result == "success") {
                    form.reset();
                    var d = new Date();
                    d.setMinutes(d.getMinutes() + 90);
                    document.getElementById('name1').innerHTML = "<b>Фамилия:</b> " + result.name1;
                    document.getElementById('name2').innerHTML = "<b>Имя:</b> " + result.name2;
                    document.getElementById('name3').innerHTML = "<b>Отчество:</b> " + result.name3;
                    document.getElementById('mail').innerHTML = "<b>E-mail:</b> " + result.mail;
                    document.getElementById('phone').innerHTML = "<b>Телефон:</b> " + result.phone;
                    document.getElementById('msg').innerHTML = result.msg;
                    document.getElementById('wait').innerHTML = "С Вами свяжутся после " + d.getHours() + ":" +
                        d.getMinutes() + ":" + d.getSeconds() + "  " +
                        d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear() + ".";
                    form.classList.add('_hide');
                    formS.classList.remove('_hide');
                    form.classList.remove('_sending');
                } else {
                    form.classList.remove('_sending');
                    alert(result.status);
                }
            }
        }
        else {
                    document.getElementById('error_label').innerHTML = 'Заполните обязательные(*) поля!';
                    document.getElementById('error_label').classList.remove('_hide');
        }
    }


    function formValidate(form){
        let error = 0;
        let formReq = document.querySelectorAll('._req');
        for (let index = 0; index < formReq.length; index++){
            const  input = formReq[index];
            formRemoveError(input);
            if (input.classList.contains('_email')){
                if (emailTest(input)){
                    formAddError(input);
                    error++;
                }
            }
            if (input.classList.contains('_phone')){
                if (phoneTest(input)){
                    formAddError(input);
                    error++;
                }
            }
            if (input.classList.contains('_name')){
                if (input.value === ''){
                    formAddError(input);
                    error++;
                }
            }
        }
        return error;
    }
    function formAddError(input){
        input.parentElement.classList.add('_error');
        input.classList.add('_error');
    }
    function formRemoveError(input){
        input.parentElement.classList.remove('_error');
        input.classList.remove('_error');
    }
    function emailTest(input){
        return !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,8})+$/.test(input.value);
    }
    function phoneTest(input){
        return !/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/.test(input.value);
    }
});