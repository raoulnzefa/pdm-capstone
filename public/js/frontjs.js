
const links = document.querySelectorAll("#navbarSupportedContent .nav-item .nav-link");

const currentURL = window.location.href;

links.forEach(function(link) {
    if (link.href === currentURL) {
       link.className += ' link-active'
    }
});

const myAccountLists = document.querySelectorAll("#my-account-sidenav .list-group-item-action");

myAccountLists.forEach(function(link) {
    if (link.href === currentURL) {
        link.parentNode.classList.add("my-account-link-active");
    }
});
