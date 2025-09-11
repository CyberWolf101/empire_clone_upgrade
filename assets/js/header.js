window.addEventListener('load', () => {
    const header_menu = document.querySelector('.header-menu');
    const menu = document.querySelector('.menu');
    const bx_menu = document.querySelector('.bx-menu');
    const soft = document.querySelector('.soft');
    const main = document.querySelector('main')
    const header = document.querySelector('header');
    let clicked = 0;

    header_menu.addEventListener('click', () => {
        main.style.display = 'none'
        header.style.display = 'block'
    })

    menu.addEventListener('click', () => {
        main.style.display = 'block'
        header.style.display = 'none'
    })

 function ToExecute(){
       main.style.display = 'none'
        header.style.display = 'block'    
 }

})