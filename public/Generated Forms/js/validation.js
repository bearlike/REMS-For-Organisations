console.log("Imported");
function verify(){
	console.log("Called");
	var form_elements = document.getElementById("entry_form").elements;
	var name = document.getElementsByClassName("name");
	var regno = document.getElementsByClassName("regno");
	var year = document.getElementsByClassName("year");
	var college = document.getElementsByClassName("college");
	var github = document.getElementsByClassName("github");
	var email = document.getElementsByClassName("email");
	var phoneno = document.getElementsByClassName("phoneno");
	var linkedin = document.getElementsByClassName("linkedin");
	console.log(form_elements);

	empty_flag =  verify_empty(form_elements);
	regno_flag =  verify_regno(regno);
	email_flag =   verify_email(email);
	phoneno_flag =  verify_phoneno(phoneno);
	github_flag =  verify_github(github);
	linkedin_flag =  verify_linkedin(linkedin);
	form_verified = empty_flag && regno_flag && email_flag && phoneno_flag && github_flag && linkedin_flag;
	console.log(form_verified);
	return form_verified;
}

function verify_empty(eles){
	flag=true;
	if(eles.length!=0){
		for(var i=0;i<eles.length;i++){
			var p = document.getElementById(eles[i].name);
			if(eles[i].type!="submit" && eles[i].type!="hidden"){
					if(eles[i].value.replace(" ","").length == 0){
					p.innerHTML = "* Required";
					p.style.display = "block";
					flag=false;
				}else{
					p.style.display = "none";
				}
			}
		}
	}
	console.log("Empty field verifications:"+flag);
	return flag;
}

function verify_regno(regnos){
	var regex = /^[0-9]{9}$/;
	var flag=true;
	if(regnos.length!=0){
		for(var i=0;i<regnos.length;i++){
			var test = regex.test(regnos[i].value);
			var p = document.getElementById(regnos[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect format | Example: 170501068";
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	console.log("Regno verification:"+flag);
	return flag;
}

function verify_email(emails){
	var regex = /^[a-zA-Z0-9.\-_]*@[a-zA-Z0-9]*\.[A-Za-z0-9.]*$/;
	var flag=true;
	if(emails.length!=0){
		for(var i=0;i<emails.length;i++){
			var test = regex.test(emails[i].value);
			var p = document.getElementById(emails[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect format | Example: example@example.com";
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	console.log("Email verification:"+flag);
	return flag;
}

function verify_phoneno(phonenos){
	var regex = /^[0-9]{10}$/;
	var flag=true;
	if(phonenos.length!=0){
		for(var i=0;i<phonenos.length;i++){
			var test = regex.test(phonenos[i].value);
			var p = document.getElementById(phonenos[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect format | Must be 10 digits long";
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	console.log("Phone number verification:"+flag);
	return flag;
}

function verify_github(githubs){
	var regex = /^https:\/\/github\.com\/[A-za-z-_0-9]*$/;
	var flag=true;
	if(githubs.length!=0){
		for(var i=0;i<githubs.length;i++){
			var test = regex.test(githubs[i].value);
			var p = document.getElementById(githubs[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect URL | Example: https://github.com/user-name";
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	console.log("GitHub Link verification:"+flag);
	return flag;
}

function verify_linkedin(linkedins){
	var regex = /^(https:\/\/www.)|(www.)linkedin.com\/[a-zA-Z\/-0-9]*$/;
	var flag=true;
	if(linkedins.length!=0){
		for(var i=0;i<linkedins.length;i++){
			var test = regex.test(linkedins[i].value);
			var p = document.getElementById(linkedins[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect URL | Example: https://www.linkedin.com/in/user-name";
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	console.log("Linkedin Link verification:"+flag);
	return flag;
}