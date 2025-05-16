function toggleMenu() {
    const nav = document.querySelector('nav');
    const menuToggle = document.querySelector('.menu-toggle i');
    nav.classList.toggle('active');
    if (nav.classList.contains('active')) {
        menuToggle.classList.remove('fa-bars');
        menuToggle.classList.add('fa-times');
    } else {
        menuToggle.classList.remove('fa-times');
        menuToggle.classList.add('fa-bars');
    }
}

function toggleDropdown() {
    var menu = document.getElementById("dropdownMenu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", function(event) {
    var dropdown = document.querySelector(".dropdown");
    if (!dropdown.contains(event.target)) {
        document.getElementById("dropdownMenu").style.display = "none";
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var loginButton = document.getElementById("loginButton");
    var registerButton = document.getElementById("registerButton");
    var loginPanel = document.getElementById("loginPanel");
    var registerPanel = document.getElementById("registerPanel");
    var closeLogin = document.getElementById("closeLogin");
    var closeRegister = document.getElementById("closeRegister");

    loginButton.addEventListener("click", function (event) {
        event.preventDefault();
        loginPanel.style.display = "block";
        registerPanel.style.display = "none";
    });

    registerButton.addEventListener("click", function (event) {
        event.preventDefault();
        registerPanel.style.display = "block";
        loginPanel.style.display = "none";
    });

    closeLogin.addEventListener("click", function () {
        loginPanel.style.display = "none";
    });

    closeRegister.addEventListener("click", function () {
        registerPanel.style.display = "none";
    });

    document.addEventListener("click", function (event) {
        if (!loginPanel.contains(event.target) && event.target !== loginButton) {
            loginPanel.style.display = "none";
        }
        if (!registerPanel.contains(event.target) && event.target !== registerButton) {
            registerPanel.style.display = "none";
        }
    });
});

function applyFilter() {
    let order = document.getElementById("order").value;
    let price = document.getElementById("price").value;

    let url = new URL(window.location.href);
    url.searchParams.set("order", order);
    url.searchParams.set("price", price);

    window.location.href = url.toString(); // Reload halaman dengan parameter baru
}