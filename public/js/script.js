function show() {
    document.getElementById("myDiv").style.display="block";
}
function logout(){
    event.preventDefault();
    document.getElementById('logout-form').submit();

}
function check() {
    if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerHTML = 'Match';
        document.getElementById('submit').disabled = false;
    } 
    else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Not Match';
        document.getElementById('submit').disabled = true;
    }
}
