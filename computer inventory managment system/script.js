   
      function showLoginForm() {
        document.body.style.backgroundColor = '#080710';
        document.getElementById('animationContainer').style.display = 'none'; 
        document.getElementById('loginForm').style.display = 'block'; 
    }

    setTimeout(showLoginForm, 8000); 

  
    setTimeout(function() {
        document.getElementById('welcomeText').classList.remove('fadeIn'); 
    }, 8000);

    const languages = [
        "Welcome",       
        "स्वागत छ",     
        "स्वागत है",     
        "ようこそ",       
        "Willkommen"      
    ];

    let index = 0;
    const welcomeElement = document.getElementById('welcomeText');


    function changeLanguage() {
        welcomeElement.classList.remove('language-change'); 
        setTimeout(() => {
            welcomeElement.textContent = languages[index];
            welcomeElement.classList.add('language-change'); 
        }, 500); 

        index = (index + 1) % languages.length; 
    }

   
    setInterval(changeLanguage, 2000);

   
    changeLanguage();


    document.getElementById('loginForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        if (username === 'admin' && password === 'admin575859') {
            window.location.replace("mainpage.php"); 
        } else {
            alert("Invalid username or password.");
        }
    });