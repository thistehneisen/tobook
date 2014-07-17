function onSignOut(){
	$.ajax({
        url: "/async-signOut.php",
        dataType : "json",
        type : "POST",
        success : function(data){
            if(data.result == "success"){
                window.location.href = "index.php";
                return;
            }else{
                alert("Sign Out Failed");
                return;
            }
        }
    });	
}