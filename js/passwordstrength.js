function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;

    // award every unique letter until 5 repetitions
    var letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
    }

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 10;

    return parseInt(score);
}

function checkPassStrength(pass) {
    var score = scorePassword(pass);
    if (score > 80)
        return "Mocne";
    if (score > 60)
        return "Dobre";
    if (score >= 30)
        return "Slabe";

    return "Żart";
}

var password = document.getElementById('pwd');
var text = document.getElementById('password-strength-text');
password.addEventListener('change', doThing);

function doThing(){
	var val = checkPassStrength(this.value);
	if (this.value !== "") {
    text.innerHTML = "Siła hasła: " + val; 
	} else {
    text.innerHTML = "";
	}
}

$('#Form input').on( 'input', function() {
	alert(pwd.value);
	
}); 