

var error_message = {
	regno : 'Example - 170501068',
	email: 'Example - example@example.com',
	phoneno: 'Must be 10 digits long',
	github: 'Example - https://github.com/user-name',
	linkedin: 'Example - https://www.linkedin.com/in/user-name'
};

var value_expression = {
	regno: /^[0-9]{9}$/,
	email: /^[a-zA-Z0-9.\-_]*@[a-zA-Z0-9]*\.[A-Za-z0-9.]*$/,
	phoneno: /^[0-9]{10}$/,
	github: /^https:\/\/github\.com\/[A-za-z-_0-9]*$/,
	linkedin: /^(https:\/\/www.)|(www.)linkedin.com\/[a-zA-Z\/-0-9]*$/
};


function verify(){
	var form_elements = document.getElementById("entry_form").elements;
	var name = document.getElementsByClassName("name");
	var regno = document.getElementsByClassName("regno");
	var year = document.getElementsByClassName("year");
	var college = document.getElementsByClassName("college");
	var github = document.getElementsByClassName("github");
	var email = document.getElementsByClassName("email");
	var phoneno = document.getElementsByClassName("phoneno");
	var linkedin = document.getElementsByClassName("linkedin");


	empty_flag =  verify_empty(form_elements);
	regno_flag =  verify_pattern(value_expression.regno,regno,error_message.regno);
	email_flag =   verify_pattern(value_expression.email,email,error_message.email);
	phoneno_flag =  verify_pattern(value_expression.phoneno,phoneno,error_message.phoneno);
	github_flag =  verify_pattern(value_expression.github,github,error_message.github);
	linkedin_flag =  verify_pattern(value_expression.linkedin,linkedin,error_message.linkedin);
	form_verified = empty_flag && regno_flag && email_flag && phoneno_flag && github_flag && linkedin_flag;


	return form_verified;
}

function verify_pattern(pattern,comps,error_message){
	var regex = pattern;
	var flag=true;
	if(comps.length!=0){
		for(var i=0;i<comps.length;i++){
			var test = regex.test(comps[i].value);
			var p = document.getElementById(comps[i].name);
			if(test==false){
				flag=false;
				p.innerHTML = "* Incorrect format | "+error_message;
				p.style.display = "block";
			}else{
				p.style.display = "none";
			}
		}
	}
	return flag;
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


