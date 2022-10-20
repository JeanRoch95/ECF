
const btn = document.querySelector('#sidebarCollapse');
const sidebar = document.querySelector('#sidebar');
const content = document.querySelector('#content');

function click(){
    sidebar.classList.toggle('active')
    content.classList.toggle('active')
}
btn.addEventListener("click", click);