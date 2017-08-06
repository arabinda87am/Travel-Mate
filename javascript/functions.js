function checkPasswordMatch() {
    var pass1 = document.getElementById("pass").value;
    var pass2 = document.getElementById("cpass").value;
    if(pass1 === pass2) {
        return;
    }
    else
    {
        alert("Password and Confirmpassword mismatch")
        document.signup.pass.focus();
    }
}
function ValidateEmail(inputText)  
{  
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
    if(inputText.value.match(mailformat))  
    {  
        document.signup.pass.focus();  
        return true;  
    }  
    else  
    {  
        alert("You have entered an invalid email address!");  
        document.signup.email.focus();  
        return false;  
    }  
}
function mobileNumber(){

     var Number = document.getElementById('mobile').value;
     var IndNum = /^[0]?[789]\d{9}$/;

     if(Number.match(IndNum)){
        return;
    }
    else{
        alert("You have entered an invalid Mobile Number!");
        document.getElementById('mobile').focus();
    }

}
function CheckPassword(inputtxt)   
{   
    var passw=  /^[A-Za-z]\w{7,14}$/;  
    if(inputtxt.value.match(passw))   
    {     
        return true;  
    }  
    else  
    {   
        alert('Password Not good..!! Please use Atleast a Capital letter and a small letter and also not less than 7 letter..!!')  
        return false;  
    }  
}