<?php
$header = __DIR__ . "/partials/header.view.php";
// Titel der Seite eintragen
$title = "Registrieren";
$page = 'register';
include_once($header);
?>
<link rel="stylesheet" href="/styles/login.css"/>
<div class="card" style="margin: 5% auto">
    <h1>Neuer Account</h1>
    <form action="/register" method="post" oninput="showButton()">
        <label id="register-forname-input-label" class="label" for="register-forname-input-field">Vorname</label>
        <input id="register-forname-input-field" class="input-field" type="text" name="forename"
               value='<?php prefill("forename"); ?>'>
        <label id="register-surname-input-label" class="label" for="register-surname-input-field">Nachname</label>
        <input id="register-surname-input-field" class="input-field" type="text" name="surname"
               value='<?php prefill("surname"); ?>'>
        <label id="register-password-input-label" class="label" for="register-password-input-field">Passwort</label>
        <input id="register-password-input-field" class="input-field" type="password" name="password"
               value='<?php prefill("password"); ?>' oninput="showPasswordRepeat()">
        <div id="repeat-password" style="display:none">
            <label id="register-password-repeat-input-label" class="label" for="register-password-repeat-input-field">Passwort
                bestätigen</label>
            <input id="register-password-repeat-input-field" class="input-field" type="password" name="password_confirm"
                   value='<?php prefill("password_confirm"); ?>' oninput="checkEqual()">
        </div>
        <label id="register-email-input-label" class="label" for="register-email-input-field">E-Mail</label>
        <input id="register-email-input-field" class="input-field" type="text" name="email"
               value='<?php prefill("email"); ?>'>
        <br/>
        <button id="sendButton" class="btn-secondary" type="submit" disabled>Registrieren</button>
    </form>
</div>
<script>
    function showPasswordRepeat(){
        let password=document.getElementById('register-password-input-field')
        let repeat=document.getElementById('repeat-password')
        if(password.value!= ""){
            repeat.style.display='block';
        }else{
            repeat.style.display='none';
        }
    }

    function showButton(){
        forname=document.getElementById('register-forname-input-field')
        surname=document.getElementById('register-surname-input-field')
        email=document.getElementById('register-email-input-field')

        let button = document.getElementById('sendButton')

        if(forname.value !="" && surname.value !="" && email.value != "" &&checkEqual()){
            button.disabled=false
        }else{
            button.disabled=true
        }
    }

    function checkEqual(){
        let password=document.getElementById('register-password-input-field')
        let repeat=document.getElementById('register-password-repeat-input-field')

        if(password.value != "" && password.value == repeat.value){
            return true
        }else{
            return false
        }
    }
</script>
<?php
$footer = __DIR__ . "/partials/footer.view.php";
include_once($footer);
?>
