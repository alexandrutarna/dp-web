function validateForm() {
	var errors = [];
    var username = document.forms["register"]["username"].value;
    var password = document.forms["register"]["password"].value;

    if (!validateEmail(username)) {
        errors.push("Username must be an email");
    }
	if (!validatePassword(password)) {
        errors.push("Password must contain at least a lowercase letter, an uppercase letter and a number");
    }
	
	if(errors.length>0) {
		alert(errors.join("\n"))
		return false;
	}
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function validatePassword(password) {
    return hasNumbers(password)&&hasLowerLetters(password);
}
function hasNumbers(t) {
	return /\d/.test(t);
}

function hasLowerLetters(t) {
    return /[a-z]/.test(t);
}